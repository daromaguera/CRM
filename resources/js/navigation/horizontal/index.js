export default [
  {
    title: 'CRM',
    to: { name: 'root' },
    icon: { icon: 'mdi-format-list-bulleted' },
  },
  {
    title: 'Appointments',
    to: { name: 'appointments' },
    icon: { icon: 'mdi-alarm' },
  },
  {
    title: 'Drip Campaigns',
    to: { name: 'drip-campaigns' },
    icon: { icon: 'mdi-bullhorn' },
  },
  {
    title: 'Trainings',
    to: { name: 'trainings', query: { tab: 'tab-1' } },
    icon: { icon: 'mdi-play-circle-outline' },
  },
  {
    title: 'Contracts',
    to: { name: 'contracts' },
    icon: { icon: 'mdi-file-outline' },
  },
  {
    title: 'Administrator',
    icon: { icon: 'mdi-monitor-multiple' },
    children: [
      {
        title: 'Companies',
        to: { name: 'administrator-admin-tab-tab', params: { tab: 'tab-1' } },
      },
      {
        title: 'Training Library',
        to: { name: 'administrator-training-tab-tab', params: { tab: 'tab-1' } },
      },
    ],
  },
  {
    title: 'Settings',
    icon: { icon: 'mdi-account-settings-variant' },
    children: [
      {
        title: 'Account',
        to: { name: 'settings-tab', params: { tab: 'account' } },
      },
    ],
  },
  {
    title: 'User',
    icon: { icon: 'mdi-human-greeting' },
    children: [
      {
        title: 'Profile',
        to: 'user-user-profile',
      },
    ],
  },
]
