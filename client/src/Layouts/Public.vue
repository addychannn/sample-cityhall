<template>
  <Layout class="!relative">
    <Header>
      <PublicNavBar :links="links" :show="componentOptions.showMobileNav" @mobileToggle="toggleMobileNav" @navigated="mobileNavigated"></PublicNavBar>
    </Header>
    <PageContainer>
      <router-view v-slot="{ Component }">
        <transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          enter-active-class="transition duration-300 delay-300"
          leave-active-class="transition duration-300"
        >
          <component :is="Component"> </component>
        </transition>
      </router-view>
    </PageContainer>

    <Footer
      class="flex rotate-180  border-b bg-gray-100 p-0 text-slate-900 drop-shadow transition-colors dark:bg-slate-900 dark:text-slate-50"
    >
      <ExpandedFooter class="rotate-180" />
    </Footer>
    <TScrollUp
      class="bottom-14 right-8 z-50 rounded-2xl bg-gray-900/75 py-2 px-5 text-gray-100 shadow-md dark:bg-gray-500/75"
    >
      <TIcon name="keyboard_double_arrow_up" size="sm" />
    </TScrollUp>

  </Layout>
</template>

<script>
import { defineComponent } from 'vue';
import PublicNavBar from './Partials/PublicNavBar.vue';
import HomePage from '../pages/public/Home.vue';
import About from '../pages/public/About.vue';
import Articles from '../pages/public/Articles/Articles.vue';
import Events from '../pages/public/Events/Events.vue';
import ReportViolation from '../pages/public/ReportViolation.vue';
import Testimonials from '../pages/public/Testimonials.vue';
import Quitter from '../pages/public/Quitter.vue';
import Ordinance from '../pages/public/Ordinance.vue';
import EnforcersRoom from '../pages/public/EnforcersRoom/EnforcersRoom.vue';
import ReminderContent from '../pages/public/EnforcersRoom/ReminderContent.vue';
import MonthlyReport from '../pages/public/EnforcersRoom/MonthlyReport.vue';
//import ReadyEnforcer from '../pages/public/EnforcersRoom/ReadyEnforcer.vue';
//import EnforcementTeam from '../pages/public/EnforcersRoom/EnforcementTeam.vue';
//import SpotViolation from '../pages/public/EnforcersRoom/SpotViolation.vue';

import ExpandedFooter from './Footer/Expanded.vue';


export default defineComponent({
    name: 'PublicLayout',
    setup(){},
    components:{
        PublicNavBar,
        HomePage,
        About,
        Articles,
        Events,
        ReportViolation,
        Testimonials,
        Quitter,
        ExpandedFooter,
        Ordinance,
        EnforcersRoom,
        ReminderContent,
        MonthlyReport,
        // ReadyEnforcer,
        // EnforcementTeam,
        // SpotViolation
    },
    data(){
        return{
            componentOptions: {
              showMobileNav: false
            },
            links: [
                {
                    name: 'publicHome',
                    alt: 'HOME'
                },
                {
                    name: 'About',
                    alt: 'ABOUT'
                },
                {
                    name: 'publicEvents',
                    alt: 'EVENTS'
                },
                {
                    name: 'publicArticles',
                    alt: 'ARTICLES'
                },
                {
                    name: 'Testimonials',
                    alt: 'TESTIMONIALS'
                },
                {
                    name: 'ReportViolation',
                    alt: 'REPORT'
                },
                {
                    name: 'Quitter',
                    alt: 'QUITTER'
                },
                {
                    name: 'Track',
                    alt: 'TRACK'
                },
                {
                    name: 'EnforcersRoom',
                    alt: 'ENFORCERS'
                },
            ]
        }
    },
    methods:{
      toggleMobileNav(){
        this.componentOptions.showMobileNav = !this.componentOptions.showMobileNav
      },
      mobileNavigated(){
        this.componentOptions.showMobileNav = false
      }
    }
})
</script>
