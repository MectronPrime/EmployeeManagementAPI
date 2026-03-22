export default [
    {
       path: '/employee-management',
       component: import("@layout/layout.vue"),

       children: [
              { 
                path: '/',
                component: import("@/views/employee-management/index.vue")
              },
              {
                path: "CreateEmployee",
                component: import("@/views/employee-management/CreateEmployee.vue")
              }
       ]

    },
];