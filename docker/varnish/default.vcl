vcl 4.0;

import std;

backend default { # Define one backend
    .host = "localhost"; # IP or Hostname of backend
    .port = "8080"; # Port Apache or whatever is listening
}

acl ban {
    # ACL we'll use later to allow purges
    "localhost";
    "127.0.0.1";
    "::1";
}

sub vcl_recv {
    # Normalize the query arguments
    set req.url = std.querysort(req.url);

    # Remove the proxy header (see https://httpoxy.org/#mitigate-varnish)
    unset req.http.proxy;
    unset req.http.forwarded;

    # To allow API Platform to ban by cache tags
    if (req.method == "BAN") {
        if (client.ip !~ ban) {
            return(synth(405, "Not allowed"));
        }

        if (req.http.ApiPlatform-Ban-Regex) {
            ban("obj.http.Cache-Tags ~ " + req.http.ApiPlatform-Ban-Regex);

            return(synth(200, "Ban added"));
        }

        return(synth(400, "ApiPlatform-Ban-Regex HTTP header must be set."));
    }

    # Don't cache in dev mode
    if (req.url ~ "^/app_dev.php") {
        return(pass);
    }

    # Only deal with "normal" types
    if (req.method != "GET" &&
        req.method != "HEAD" &&
        req.method != "PUT" &&
        req.method != "POST" &&
        req.method != "TRACE" &&
        req.method != "OPTIONS" &&
        req.method != "PATCH" &&
        req.method != "DELETE") {
        # Non-RFC2616 or CONNECT which is weird.
        return (pipe);
    }
}

sub vcl_hit {
    if (obj.ttl >= 0s) {
        # Normal hit
        return (deliver);
    } elsif (std.healthy(req.backend_hint)) {
        # The backend is healthy
        # Fetch the object from the backend
        return (fetch);
    } else {
        # No fresh object and the backend is not healthy
        if (obj.ttl + obj.grace > 0s) {
            # Deliver graced object
            # Automatically triggers a background fetch
            return (deliver);
        } else {
            # No valid object to deliver
            # No healthy backend to handle request
            # Return error
            return (synth(503, "API is down"));
        }
    }
}

# Handle the HTTP request coming from our backend
sub vcl_backend_response {
    if (beresp.status == 500 || beresp.status == 502 || beresp.status == 503 || beresp.status == 504) {
        return (abandon);
    }

    set beresp.http.url = bereq.url;
    set beresp.grace = 1h;
}

# The routine when we deliver the HTTP request to the user
# Last chance to modify headers that are sent to the client
sub vcl_deliver {
    if (obj.hits > 0) { # Add debug header to see if it's a HIT/MISS and the number of hits, disable when not needed
        set resp.http.X-Cache = "HIT";
    } else {
        set resp.http.X-Cache = "MISS";
    }

    # Please note that obj.hits behaviour changed in 4.0, now it counts per objecthead, not per object
    # and obj.hits may not be reset in some cases where bans are in use. See bug 1492 for details.
    # So take hits with a grain of salt
    set resp.http.X-Cache-Hits = obj.hits;

    # Remove some headers: PHP version
    unset resp.http.X-Powered-By;

    # Remove some headers: Apache version & OS
    unset resp.http.Server;
    unset resp.http.X-Drupal-Cache;
    unset resp.http.X-Varnish;
    unset resp.http.Via;
    unset resp.http.Link;
    unset resp.http.X-Generator;
    unset resp.http.X-Debug-Token;
    unset resp.http.X-Debug-Token-Link;

    set resp.http.X-Powered-By = "MSI<surya.kejawen@gmail.com>";
}
