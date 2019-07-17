module.exports = {
  title: "Semart Skeleton",
  base: "/SemartSkeleton/",
  description: "Semart Skeleton adalah sebuah skeleton atau boilerplate atau kerangka awal untuk memulai sebuah proyek",
  redirectPath: "/id/",
  locales: {
    "/id/": {
      lang: "en-US",
      title: "Semart Skeleton",
      description: "Just playing arounds"
    }
  },

  themeConfig: {
    repo: 'https://github.com/puterakahfi/SemartSkeleton',
    // Customising the header label
    // Defaults to "GitHub"/"GitLab"/"Bitbucket" depending on `themeConfig.repo`
    repoLabel: 'Contribute!',
    lastUpdated: "Last Updated", // string | boolean
    docsBranch: "master",
    // defaults to false, set to true to enable
    editLinks: true,
    // custom text for edit link. Defaults to "Edit this page"
    editLinkText: "Help us improve this page!",
    logo: "/icon.png",
    serviceWorker: {
      updatePopup: true // Boolean | Object, default to undefined.
    },
    nav: [
      { text: "Tags", link: "/tags" },
    ],
    locales: {
      "/id/": {
        lang: "bahasa", // this will be set as the lang attribute on <html>
        title: "VuePress",
        description: "Vue-powered Static Site Generator",
        sidebar: [
          '/id/',
          'id/usage',
          '/id/menu',
          '/id/permission'
        ]
      }
    }
  }
};
