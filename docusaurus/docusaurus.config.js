/**
 * Copyright (c) 2017-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

module.exports = {
  title: 'Laravel Admin',
  tagline: '',
  url: 'https://your-docusaurus-test-site.com',
  baseUrl: '/',
  favicon: 'img/logo.png',
  organizationName: 'facebook', // Usually your GitHub org/user name.
  projectName: 'docusaurus', // Usually your repo name.
  themeConfig: {
    navbar: {
      title: 'Laravel Admin',
      logo: {
        alt: 'My Site Logo',
        src: 'img/logo.png',
      },
      links: [
        {to: 'docs/introduction', label: '文档', position: 'left'},
        {to: 'https://demo.tanecn.com/admin', label: '演示', position: 'left'},
        {
          href: 'https://github.com/tanwencn/laravel-admin',
          label: 'GitHub',
          position: 'right',
        },
      ],
    },
    footer: {
      style: 'dark',
      copyright: `Copyright © ${new Date().getFullYear()} Laravel Admin, Inc. Built with tanecn.com.`,
    },
  },
  presets: [
    [
      '@docusaurus/preset-classic',
      {
        docs: {
          sidebarPath: require.resolve('./sidebars.js'),
        },
        theme: {
          customCss: require.resolve('./src/css/custom.css'),
        },
      },
    ],
  ],
};
