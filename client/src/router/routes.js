export default [
  // PUBLIC USER ROUTES
  {
    path: "/",
    component: () => import("@/Layouts/Public.vue"),
    children: [ 
      {
        path: "/",
        name: "publicHome",
        component: () => import("@/pages/public/home.vue"),
       
      },

      {
        path: "/about",
        name: "About",
        component: () => import("@/pages/public/about.vue"),
      },
      {
        path: "/articles/list",
        name: "publicArticles",
        component: () => import("@/pages/public/articles/articles.vue"),
      },
      {
        path: "/articles/:hash",
        name: "PublicViewArticle",
        component: () => import("@/pages/public/articles/publicViewArticle.vue"),
      },
      
      {
        path: "/events",
        name: "publicEvents",
        component: () => import("@/pages/public/events/events.vue"),
      },
      {
        path: "/events/:hash",
        name: "PublicViewEvent",
        component: () => import("@/pages/public/events/publicViewEvent.vue"),
      },
      
      {
        path: "/reportViolation",
        name: "ReportViolation",
        component: () => import("@/pages/public/reportViolation.vue"),
      },
      {
        path: "/quitter",
        name: "Quitter",
        component: () => import("@/pages/public/quitter.vue"),
      },
      {
        path: "/track",
        name: "Track",
        component: () => import("@/pages/public/track.vue")
      },
      {
        path: "/testimonials",
        name: "Testimonials",
        component: () => import("@/pages/public/testimonials.vue"),
        
      },
      {
        path: "/testimonials/:hash",
        name: "public_view_testimony",
        component: () => import("@/pages/public/testimonyContent.vue"),
      },
      {
        path: "/ordinance",
        name: "Ordinance",
        component: () => import("@/pages/public/ordinance.vue"),
        
      },
      {
        path: "/enforcers-room",
        name: "EnforcersRoom",
        component: () => import("@/pages/public/enforcersRoom/enforcersRoom.vue"),
        
      },
    ],
  },
  // ADMIN ROUTES
  {
    path: "/home",
    component: () => import("@/Layouts/Admin.vue"),
    meta:{
      requiresAuth: true //checks if user logged in else, it will redirect user to log in page
    },
    children:[
      //home links
      {
        path: "/home",
        name: "AdminHome",
        component: () => import("@/pages/admin/Home.vue")
      },
      //quitter links
      {
        path: "/quitter-management",
        name: "QuitterManagement",
        component: () => import("@/pages/admin/quitter/quitter.vue"),
      },
      {
        path: "/quitter-reject",
        name: "ListRejectedQuitter",
        component: () => import("@/pages/admin/quitter/listRejectedQuitter.vue"),
      },
      {
        path: "/quitter-accept",
        name: "ListAcceptedQuitter",
        component: () => import("@/pages/admin/quitter/listAcceptedQuitter.vue"),
      },
      //report links
      {
        path: "/report-management",
        name: "ReportManagement",
        component: () => import("@/pages/admin/report/report.vue"),
      },
      {
        path: "/report-reject",
        name: "ListRejectReport",
        component: () => import("@/pages/admin/report/listRejectReport.vue"),
      },
      {
        path: "/report-resolve",
        name: "ListResolvedReport",
        component: () => import("@/pages/admin/report/listResolvedReport.vue"),
      },
      //testimony links
      {
        path: "/admin/testimonials",
        name: "AdminTestimonials",
        component: () => import("@/pages/admin/testimonials/testimonials.vue"),
      },
      {
        path: "/testimony-deleted",
        name: "ListDeletedTestimony",
        component: () => import("@/pages/admin/testimonials/listDeletedTestimony.vue"),
      },
      {
        path: "/testimony-details/:hash",
        name: "ViewDeletedTestimony",
        component: () => import("@/pages/admin/testimonials/viewDeletedTestimony.vue"),

      },
      {
        path: "/admin/testimony/:hash", 
        name: "ViewTestimony",
        component: () => import("@/pages/admin/testimonials/viewTestimony.vue"),   
      },
      {
        path: "/admin/CreateTestimony", 
        name: "CreateTestimony",
        component: () => import("@/pages/admin/testimonials/createTestimony.vue"),   
      },
      {
        path: "/admin/edit-testimony/:hash", 
        name: "testimony-edit",
        component: () => import("@/pages/admin/testimonials/editTestimony.vue")
      },


      //event links
      {
        path: "/admin/events",
        name: "Events",
        component: () => import("@/pages/admin/events/events.vue"),
      },
      {
        path: "/admin/events/:hash", 
        name: "EditEvent",
        component: () => import("@/pages/admin/events/editEvent.vue"),
        
      },
      {
        path: "/admin/events/add-event", 
        name: "AddEvent",
        component: () => import("@/pages/admin/events/addEvent.vue"),
        
      },
      {
        path: "/admin/events/:hash", 
        name: "ViewEvent",
        component: () => import("@/pages/admin/events/viewEvent.vue"),
        
      },
      {
        path: "admin/adminevents/ListOfDeletedEvents", 
        name: "ListOfDeletedEvents",
        component: () => import("@/pages/admin/events/listOfDeletedEvents.vue"),
      },
      {
        path: "admin/events/deletedEvents/:hash", 
        name: "ViewDeletedEvent",
        component: () => import("@/pages/admin/events/viewDeletedEvent.vue"),
      },
      //article links
      {
        path: "/admin/articles",
        name: "Articles",
        component: () => import("@/pages/admin/articles/articles.vue"),
        
      },
      {
        path: "/admin/articles/:hash",
        name: "ViewArticle",
        component: () => import("@/pages/admin/articles/viewArticle.vue"),
        
      },
      {
        path: "/admin/articles/:hash",
        name: "EditArticle",
        component: () => import("@/pages/admin/articles/editArticle.vue"),
        
      },
    
      {
        path: "/admin/articles/add-article",
        name: "AddArticle",
        component: () => import("@/pages/admin/articles/addArticle.vue"),
      },
      {
        path: "/admin/articles/ListOfDeletedArticles",
        name: "ListOfDeletedArticles",
        component: () => import("@/pages/admin/articles/listOfDeletedArticles.vue"),
      },
      {
        path: "/admin/articles/ListOfDeletedArticles/:hash",
        name: "ViewDeletedArticle",
        component: () => import("@/pages/admin/articles/viewDeletedArticle.vue"),
      },
  // message links
      {
        path: "/admin/messages/list",
        name: "Messages",
        component: () => import("@/pages/admin/messages/messages.vue"),
      },
      {
        path: "admin/messages/:hash",
        name: "ViewMessages",
        component: () => import("@/pages/admin/messages/viewMessages.vue"),
        props:true
      
      },
      {
        path: "admin/messages/ListOfDeletedMessages",
        name: "ListOfDeletedMessages",
        component: () => import("@/pages/admin/messages/listOfDeletedMessages.vue"),
      
      },
      //about links
      {
        path: "/about-management",
        name: "AdminAbout",
        component: () => import("@/pages/admin/about/about.vue")
      },
      //ordinance links
      {
        path: "/admin/ordinance",
        name: "OrdinanceManagement",
        component: () => import("@/pages/admin/ordinance/ordinance.vue"),
        meta: {
          title: "Ordinance",
        },
      },
     
      
      //user management links
      {
        path: "/admin/user-management",
        name: "UserManagement",
        component: () => import("@/pages/admin/user/userManagement.vue"),
        
      },
      {
        path: "admin/usermanagement/ViewUser/:hash", 
        name: "view-user",
        component: () => import("@/pages/admin/user/viewUser.vue"),
        
      },
      {
        path: "admin/edit-credentials/:hash", 
        name: "edit-credentials",
        component: () => import("@/pages/admin/user/editUserCredentials.vue"),
        
      },
      {
        path: "admin/edit-profile/:hash", 
        name: "edit-profile",
        component: () => import("@/pages/admin/user/editUserProfile.vue"),
        
      },
      {
        path: "admin/set-permission/:hash", 
        name: "set-permission",
        component: () => import("@/pages/admin/user/setUserPermissions.vue"),
        
      },
      {
        path: "admin/usermanagement/ViewImage/:hash", 
        name: "view-images",
        component: () => import("@/pages/admin/user/viewUserImages.vue"),
        
      },
      {
        path: "admin/usermanagement/manage-image/:hash",
        name: "manage-images",
        component: () => import("@/pages/admin/user/manageImages.vue")
      },

      //logs link
      {
        path: "/admin/logs",
        name: "Logs",
        component: () => import("@/pages/admin/Logs.vue"),
        
      },

      {
        path: "/admin/about/create",
        name: "CreateSection",
        component: () => import("@/pages/admin/about/createAboutSection.vue"),
        
      },
      {
        path: "/admin/about/update/:hash",
        name: "UpdateSection",
        component: () => import("@/pages/admin/about/editAboutSection.vue"),
        props: true
        
      },
      // {
      //   path: "/admin/about/carousel/:hash",
      //   name: "AboutCarousel",
      //   component: () => import("@/pages/admin/About/Carousel.vue"),
      //   props: true
      // },

      //enforcer links
      {
        path: "/admin/enforcerRoom",
        name: "EnforcerRoom",
        component: () => import("@/pages/admin/enforcerRoom/enforcerRoom.vue"),
        props: true
      },
      {
        path: "/admin/enforcer-room/file/:hash",
        name: "ViewUploadedForm",
        component: () => import("@/pages/admin/enforcerRoom/viewForm.vue"),
        props: true
      },
      {
        path: "/admin/UploadedForms",
        name: "UploadedForms",
        component: () => import("@/pages/admin/enforcerRoom/uploadedForms.vue"),
        props: true
      },
      {
        path: "/admin/enforcer-reminders-create",
        name: "CreateReminder",
        component: () => import("@/pages/admin/enforcerRoom/createReminder.vue"),
        props: true
      },

      {
        path: "/admin/enforcer-reminders-edit/:id",
        name: "EditReminder",
        component: () => import("@/pages/admin/enforcerRoom/editReminder.vue"),
        props: true
      },

      {
        path: "/admin/enforcer-reminders",
        name: "EnforcerReminders",
        component: () => import("@/pages/admin/enforcerRoom/reminders.vue"),
        props: true
      },

      {
        path: "/admin/ListOfDeletedForms",
        name: "ListOfDeletedForms",
        component: () => import("@/pages/admin/enforcerRoom/listOfDeletedForms.vue"),
        props: true
      },
      //roles
      {
        path:"/roles",
        name:"RolesManagement",
        component: () => import("@/pages/admin/Roles/listRoles.vue"),
      },
      {
        path:"/add-role",
        name:"AddRole",
        component: () => import("@/pages/admin/Roles/addRole.vue"),
      },
      {
        path:"/edit-role/:hash",
        name:"EditRole",
        component: () => import("@/pages/admin/Roles/editRole.vue"),
      },

      //unauthorized
      {
        path: "/unauthorized",
        name: "Unauthorized",
        component: () => import("@/layouts/Partials/Unauthorized.vue"),
      },
    ]
  },
  //settings
  {
    path: "/settings",
    component: () => import("@/Layouts/Main.vue"),
    meta: {
      requiresAuth: true,
    },
    children: [
      {
        path: "/settings",
        name: "settings",
        component: () => import("@/pages/Settings/index.vue"),
        children: [
          {
            path: "/settings/users",
            component: () => import("@/pages/Settings/Users/index.vue"),
            name: "settings-users",
          },
          {
            path: "/settings/carousel",
            component: () => import("@/pages/Settings/Carousel/index.vue"),
            name: "settings-carousel",
          },
          {
            path: "/products",
            name: "ProductManagementPage",
            component: () => import('@/pages/Products/index.vue'),
          },
          // create new route - 06-03
          {
            path: "/products/:hash",
            name: "product-edit",
            component: () => import('@/pages/Products/forms/EditForm.vue'),
          }
        ],
      },
    ],
  },
  //auth
  {
    path: "/",
    component: () => import("@/Layouts/Auth.vue"),
    children: [
      {
        path: "/login",
        name: "login",
        component: () => import("@/pages/authentication/login.vue"),
        meta: {
          requiresVisitor: true,
        },
      },
      {
        path: "/unauthorized",
        name: "401",
        component: () => import("@/pages/error/401.vue"),
        meta: {
          title: "Unauthorized",
        },
      },
      {
        path: "/forbidden",
        name: "403",
        component: () => import("@/pages/error/403.vue"),
        meta: {
          title: "Forbidden",
        },
      },
      // Always leave this as last one,
      // but you can also remove it
      {
        path: "/:catchAll(.*)*",
        name: "notFound",
        component: () => import("@/pages/error/404.vue"),
        meta: {
          title: "Not Found",
        },
      },
    ],
  },
];
