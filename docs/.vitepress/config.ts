import { defineConfig } from 'vitepress';

const repository = process.env.GITHUB_REPOSITORY?.split('/')[1] ?? '';
const isPagesBuild = process.env.GITHUB_ACTIONS === 'true';
const isUserOrOrgSite = repository.endsWith('.github.io');

const base = isPagesBuild
  ? (isUserOrOrgSite || repository === '' ? '/' : `/${repository}/`)
  : '/';

export default defineConfig({
  title: 'wp-fluent-admin',
  description: 'A fluent PHP component library for WordPress admin pages.',
  base,
  cleanUrls: true,
  lastUpdated: true,
  themeConfig: {
    search: {
      provider: 'local',
    },
    nav: [
      { text: 'Getting Started', link: '/getting-started/installation' },
      { text: 'Components', link: '/components/page' },
      { text: 'Fields', link: '/fields/overview' },
      { text: 'Guides', link: '/guides/settings-page' },
      { text: 'Extensibility', link: '/extensibility/custom-components' },
      { text: 'Reference', link: '/reference/component-base' },
    ],
    sidebar: {
      '/getting-started/': [
        {
          text: 'Getting Started',
          items: [
            { text: 'Installation', link: '/getting-started/installation' },
            { text: 'Quick Start', link: '/getting-started/quick-start' },
            { text: 'Concepts', link: '/getting-started/concepts' },
          ],
        },
      ],
      '/components/': [
        {
          text: 'Components',
          items: [
            { text: 'Page', link: '/components/page' },
            { text: 'Notice', link: '/components/notice' },
            { text: 'Button', link: '/components/button' },
            { text: 'Button Group', link: '/components/button-group' },
            { text: 'Metabox', link: '/components/metabox' },
            { text: 'Metabox Container', link: '/components/metabox-container' },
            { text: 'Tabs', link: '/components/tabs' },
            { text: 'Form Table', link: '/components/form-table' },
            { text: 'List Table', link: '/components/list-table' },
            { text: 'Data Table', link: '/components/data-table' },
            { text: 'Spinner', link: '/components/spinner' },
            { text: 'Counter', link: '/components/counter' },
            { text: 'Dashicon', link: '/components/dashicon' },
            { text: 'Card', link: '/components/card' },
          ],
        },
      ],
      '/fields/': [
        {
          text: 'Fields',
          items: [
            { text: 'Overview', link: '/fields/overview' },
            { text: 'Text', link: '/fields/text' },
            { text: 'Textarea', link: '/fields/textarea' },
            { text: 'Select', link: '/fields/select' },
            { text: 'Checkbox', link: '/fields/checkbox' },
            { text: 'Radio', link: '/fields/radio' },
            { text: 'Password', link: '/fields/password' },
            { text: 'Color', link: '/fields/color' },
            { text: 'Media', link: '/fields/media' },
          ],
        },
      ],
      '/guides/': [
        {
          text: 'Guides',
          items: [
            { text: 'Settings Page', link: '/guides/settings-page' },
            { text: 'List Page', link: '/guides/list-page' },
            { text: 'Dashboard Page', link: '/guides/dashboard-page' },
            { text: 'Multi-Tab Settings', link: '/guides/multi-tab-settings' },
            { text: 'Custom Components', link: '/guides/custom-components' },
          ],
        },
      ],
      '/extensibility/': [
        {
          text: 'Extensibility',
          items: [
            { text: 'Custom Components', link: '/extensibility/custom-components' },
            { text: 'Custom Fields', link: '/extensibility/custom-fields' },
            { text: 'Filters', link: '/extensibility/filters' },
            { text: 'PHP-Scoper', link: '/extensibility/php-scoper' },
          ],
        },
      ],
      '/reference/': [
        {
          text: 'Reference',
          items: [
            { text: 'Component Base', link: '/reference/component-base' },
            { text: 'Traits', link: '/reference/traits' },
            { text: 'Support Classes', link: '/reference/support-classes' },
            { text: 'WordPress Markup', link: '/reference/wordpress-markup' },
          ],
        },
      ],
    },
    socialLinks: [
      { icon: 'github', link: 'https://github.com/timrross/wp-fluent-admin' },
    ],
  },
});
