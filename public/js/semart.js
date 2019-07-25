function province_autocomplete_district(provinceId, districtId, districtSelector, emptyText, callback) {
    $.get(Routing.generate('provinces_districts', {id: provinceId}), function (response) {
        let districts = JSON.parse(response);
        let options = '<option value="">' + emptyText + '</option>';
        $.each(districts, function (idx, val) {
            if (districtId === val.id) {
                options += '<option value="' + val.id + '" selected="selected">' + val.code + ' - ' + val.name + '</option>';
            } else {
                options += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
            }
        });

        let disitrictEl = $(districtSelector);
        disitrictEl.html(options);
        disitrictEl.select2();

        if ('function' === typeof callback) {
            callback();
        }
    });
}

function district_autocomplete_sub_district(districtId, subDistrictId, subDistrictSelector, emptyText, callback) {
    $.get(Routing.generate('district_sub_districts', {id: districtId}), function (response) {
        let subDistricts = JSON.parse(response);
        let options = '<option value="">' + emptyText + '</option>';
        $.each(subDistricts, function (idx, val) {
            if (subDistrictId === val.id) {
                options += '<option value="' + val.id + '" selected="selected">' + val.code + ' - ' + val.name + '</option>';
            } else {
                options += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
            }
        });

        let subDistrictEl = $(subDistrictSelector);
        subDistrictEl.html(options);
        subDistrictEl.select2();

        if ('function' === typeof callback) {
            callback();
        }
    });
}

function level_autocomplete_supervisor(level, supervisorId, supervisorSelector, emptyText, callback) {
    $.get(Routing.generate('job_titles_supervisor', {level: level}), function (response) {
        let supervisors = JSON.parse(response);
        let options = '<option value="">' + emptyText + '</option>';
        $.each(supervisors, function (idx, val) {
            if (supervisorId === val.id) {
                options += '<option value="' + val.id + '" selected="selected">' + val.code + ' - ' + val.name + '</option>';
            } else {
                options += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
            }
        });

        let supervisorEl = $(supervisorSelector);
        supervisorEl.html(options);
        supervisorEl.select2();

        if ('function' === typeof callback) {
            callback();
        }
    });
}
