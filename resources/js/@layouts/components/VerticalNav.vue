<script setup>
import { layoutConfig } from '@layouts'
import { VerticalNavGroup, VerticalNavLink, VerticalNavSectionTitle } from '@layouts/components'
import { useLayoutConfigStore } from '@layouts/stores/config'
import { injectionKeyIsVerticalNavHovered } from '@layouts/symbols'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import { VNodeRenderer } from './VNodeRenderer'

const props = defineProps({
  tag: {
    type: null,
    required: false,
    default: 'aside',
  },
  navItems: {
    type: null,
    required: true,
  },
  isOverlayNavActive: {
    type: Boolean,
    required: true,
  },
  toggleIsOverlayNavActive: {
    type: Function,
    required: true,
  },
})

const refNav = ref()
const isHovered = useElementHover(refNav)

provide(injectionKeyIsVerticalNavHovered, isHovered)

const configStore = useLayoutConfigStore()

const resolveNavItemComponent = (item) => {
  if ('heading' in item) return VerticalNavSectionTitle
  if ('children' in item) return VerticalNavGroup

  return VerticalNavLink
}

/*ℹ️ Close overlay side when route is changed
Close overlay vertical nav when link is clicked
*/
const route = useRoute()

watch(
  () => route.name,
  () => {
    props.toggleIsOverlayNavActive(false)
  }
)

const isVerticalNavScrolled = ref(false)
const updateIsVerticalNavScrolled = (val) => (isVerticalNavScrolled.value = val)

const handleNavScroll = (evt) => {
  isVerticalNavScrolled.value = evt.target.scrollTop > 0
}

const hideTitleAndIcon = configStore.isVerticalNavMini(isHovered)

import { ref, watch } from 'vue'
import { useTheme } from 'vuetify'

const theme = useTheme()

var filteredNavItems

var filteredNavItems = ref([])
if (JSON.parse(localStorage.getItem('userData'))) {
  const user_role = JSON.parse(localStorage.getItem('userData'))
  if (user_role.user_role_id === 1) {
    filteredNavItems = props.navItems
  } else {
    filteredNavItems = computed(() =>
      props.navItems.filter((item) => item.title !== 'Administrator')
    )
  }
}
// const user_role = JSON.parse(localStorage.getItem('userData'))
// if(user_role.user_role_id === 1){
//   filteredNavItems = props.navItems
// }else{
//   filteredNavItems = computed(() => props.navItems.filter(item => item.title !== 'Administrator'));
// }
</script>

<template>
  <Component
    :is="props.tag"
    ref="refNav"
    class="layout-vertical-nav"
    :class="[
      {
        'overlay-nav': configStore.isLessThanOverlayNavBreakpoint,
        hovered: isHovered,
        visible: isOverlayNavActive,
        scrolled: isVerticalNavScrolled,
      },
    ]"
  >
    <!-- 👉 Header -->
    <div class="nav-header">
      <slot name="nav-header">
        <RouterLink to="/" class="app-logo app-title-wrapper">
          <VNodeRenderer :nodes="layoutConfig.app.logo" />

          <Transition name="vertical-nav-app-title">
            <h1 v-show="!hideTitleAndIcon" class="app-logo-title leading-normal">
              {{ layoutConfig.app.title }}
            </h1>
          </Transition>
        </RouterLink>
        <!-- 👉 Vertical nav actions -->
        <!-- Show toggle collapsible in >md and close button in <md -->
        <div class="header-action">
          <Component
            :is="layoutConfig.app.iconRenderer || 'div'"
            v-show="configStore.isVerticalNavCollapsed"
            class="d-none nav-unpin"
            :class="configStore.isVerticalNavCollapsed && 'd-lg-block'"
            v-bind="layoutConfig.icons.verticalNavUnPinned"
            @click="configStore.isVerticalNavCollapsed = !configStore.isVerticalNavCollapsed"
          />
          <Component
            :is="layoutConfig.app.iconRenderer || 'div'"
            v-show="!configStore.isVerticalNavCollapsed"
            class="d-none nav-pin"
            :class="!configStore.isVerticalNavCollapsed && 'd-lg-block'"
            v-bind="layoutConfig.icons.verticalNavPinned"
            @click="configStore.isVerticalNavCollapsed = !configStore.isVerticalNavCollapsed"
          />
          <Component
            :is="layoutConfig.app.iconRenderer || 'div'"
            class="d-lg-none"
            v-bind="layoutConfig.icons.close"
            @click="toggleIsOverlayNavActive(false)"
          />
        </div>
      </slot>
    </div>
    <slot name="before-nav-items">
      <div class="vertical-nav-items-shadow" />
    </slot>
    <slot name="nav-items" :update-is-vertical-nav-scrolled="updateIsVerticalNavScrolled">
      <PerfectScrollbar
        :key="configStore.isAppRTL"
        tag="ul"
        class="nav-items"
        :options="{ wheelPropagation: false }"
        @ps-scroll-y="handleNavScroll"
      >
        <Component
          :is="resolveNavItemComponent(item)"
          v-for="(item, index) in filteredNavItems"
          :key="index"
          :item="item"
        />
      </PerfectScrollbar>
    </slot>
    <slot name="after-nav-items" />

    <div class="noteSection">
      <transition name="fade-slide" mode="out-in">
        <div class="bottomNoteAtSideBar mt-auto text-gray-950" v-if="!hideTitleAndIcon">
          <div class="d-flex justify-space-between items-center font12">
            Recent Activity
            <span class="cursor-pointer">
              <span class="cst_badge" style="z-index: 9999"> 1 </span>
              <VIcon icon="mdi-bell-outline" />
            </span>
          </div>
          <div class="mt-2" />
          <div
            :class="`bg-gray-100 rounded-md p-2 cst_noteData ${theme.name.value === 'dark' ? 'cst_noteDataDark' : 'cst_noteDataLight'}`"
          >
            <div class="d-flex justify-space-between items-center">
              Today 12:32
              <VIcon icon="mdi-arrow-right" class="fa-arrow-right" />
            </div>
            <div class="font-bold">Client Notification</div>
            <div>
              <small
                >This client notification is letting you know that the special client is in need of
                paper work from you.</small
              >
            </div>
          </div>
          <div
            :class="`bg-gray-100 rounded-md p-2 cst_noteData ${theme.name.value === 'dark' ? 'cst_noteDataDark' : 'cst_noteDataLight'}`"
          >
            <div class="d-flex justify-space-between items-center">
              Today 12:32
              <VIcon icon="mdi-arrow-right" class="fa-arrow-right" />
            </div>
            <div class="font-bold">Client Notification</div>
            <div>
              <small
                >This client notification is letting you know that the special client is in need of
                paper work from you.</small
              >
            </div>
          </div>
          <div class="cst_seeAll cursor-pointer">See All</div>
        </div>
      </transition>

      <div v-if="hideTitleAndIcon" class="hiddenBadge">
        <span>
          <span class="cst_badge" style="z-index: 9999"> 1 </span>
          <VIcon icon="mdi-bell-outline" />
        </span>
      </div>
    </div>
  </Component>
</template>

<style lang="scss" scoped>
.app-logo {
  display: flex;
  align-items: center;
  column-gap: 0.75rem;

  .app-logo-title {
    font-size: 1.25rem;
    font-weight: 500;
    line-height: 1.75rem;
    text-transform: uppercase;
  }
}
</style>

<style lang="scss" scoped>
@use '@configured-variables' as variables;
@use '@layouts/styles/mixins';

// 👉 Vertical Nav
.layout-vertical-nav {
  position: fixed;
  z-index: variables.$layout-vertical-nav-z-index;
  display: flex;
  flex-direction: column;
  block-size: 100%;
  inline-size: variables.$layout-vertical-nav-width;
  inset-block-start: 0;
  inset-inline-start: 0;
  transition:
    inline-size 0.25s ease-in-out,
    box-shadow 0.25s ease-in-out;
  will-change: transform, inline-size;

  .nav-header {
    display: flex;
    align-items: center;

    .header-action {
      cursor: pointer;

      @at-root {
        #{variables.$selector-vertical-nav-mini} .nav-header .header-action {
          &.nav-pin,
          &.nav-unpin {
            display: none !important;
          }
        }
      }
    }
  }

  .app-title-wrapper {
    margin-inline-end: auto;
  }

  .nav-items {
    block-size: 100%;

    // ℹ️ We no loner needs this overflow styles as perfect scrollbar applies it
    // overflow-x: hidden;

    // // ℹ️ We used `overflow-y` instead of `overflow` to mitigate overflow x. Revert back if any issue found.
    // overflow-y: auto;
  }

  .nav-item-title {
    overflow: hidden;
    margin-inline-end: auto;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  // 👉 Collapsed
  .layout-vertical-nav-collapsed & {
    &:not(.hovered) {
      inline-size: variables.$layout-vertical-nav-collapsed-width;
    }
  }
}

// Small screen vertical nav transition
@media (max-width: 1279px) {
  .layout-vertical-nav {
    &:not(.visible) {
      transform: translateX(-#{variables.$layout-vertical-nav-width});

      @include mixins.rtl {
        transform: translateX(variables.$layout-vertical-nav-width);
      }
    }

    transition: transform 0.25s ease-in-out;
  }
}

.bottomNoteAtSideBar {
  padding: 20px;
}
.cst_noteData {
  margin-top: 8px;
  font-size: 11px;
  border-radius: 5px;
  padding: 10px;
}
.cst_noteDataDark {
  background: #414141;
}
.cst_noteDataLight {
  background: #dddddd;
}
.cst_noteDataDark:hover {
  background: #396970;
  cursor: pointer;
}
.cst_noteDataLight:hover {
  background: #93dde9;
  cursor: pointer;
}
.cst_noteData .fa-arrow-right {
  opacity: 0; /* Make the icon invisible */
  transition: opacity 0.3s ease; /* Smooth transition for showing the icon */
}
/* Show the icon when the parent div is hovered */
.cst_noteData:hover .fa-arrow-right {
  opacity: 1; /* Make the icon visible */
}
.fa-arrow-right {
  color: #01434f;
}
.cst_seeAll {
  color: #11b8d2;
  padding: 5px;
  font-size: 12px;
}
.cst_badge {
  display: inline-block;
  background-color: rgb(219, 14, 14); /* Red background */
  color: white; /* White text */
  border-radius: 50%; /* Circular shape */
  width: 17px; /* Fixed width for the badge */
  height: 17px; /* Fixed height for the badge */
  text-align: center; /* Center the text horizontally */
  line-height: 20px; /* Center the text vertically */
  font-size: 11px; /* Text size */
  font-weight: bold; /* Optional: make the number bold */
  position: absolute;
  margin-top: -6px;
  margin-left: 8px;
}
.font12 {
  font-size: 12px;
}
.cst_bellIcon {
  font-size: 18px;
  color: rgb(219, 14, 14);
}
.hiddenBadge {
  display: flex;
  justify-content: center;
  align-items: center;
  padding-bottom: 20px;
}

/* Enter Animation */
.fade-slide-enter-active {
  transition:
    opacity 0.5s ease,
    transform 0.5s ease;
}
.fade-slide-enter-from {
  opacity: 0;
  transform: translateY(10px);
}
.fade-slide-enter-to {
  opacity: 1;
  transform: translateY(0);
}

/* Quick Leave Animation */
.fade-slide-leave-active {
  transition:
    opacity 0.1s ease,
    transform 0.1s ease;
}
.fade-slide-leave-from {
  opacity: 1;
  transform: translateY(0);
}
.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(10px);
}
</style>
