export default {
  namespaced: true,
  state:{
    service: {},
    services:[
      {
        slug: 'undercover-programer',
        color: 'green',
        title: 'Undercover Programer',
        description: "Uncover the reality behind delays and costs within your IT team. We provide an expert and independent analysis on your codebase, team and workflow, identifying productivity issues and causes of delays to delivery.",
        steps:[
          {
            order: 1,
            title: 'Infiltrate',
            description: "An expert will pose as a developer in your IT team."
          },
          {
            order: 2,
            title: "Collection",
            description: "Data will be collected on the team dynamic, codebase and workflows, focusing on issues causing delays."
          },
          {
            order: 3,
            title: "Report",
            description: "A report will be provided which will outline issues within the team, codebase and workflows and bottle necks to delivery."
          }
        ]
      },
      {
        slug: 'codebase-simplification-modernisation-analysis',
        color: 'purple',
        title: "Codebase Simplification & Modernisation Analysis",
        description: "We provide an extensive codebase analysis and formulate a road map of requirements for modernisation.",
        steps:[
          {
            order: 1,
            title: 'Define',
            description: "Define objectives and scope of your codebase within the bounds of simplification and modernisation"
          },
          {
            order: 2,
            title: "Analysis",
            description: "An expert will analysis your codebase, for complexity, code quality, convention adherence, code style."
          },
          {
            order: 3,
            title: "Report",
            description: "A report will be issued, providing you with an calculated estimate of duration to achieve the goals set out in step 1"
          }
        ]
      },
      {
        slug: 'web-solutions',
        color: 'orange',
        title: "Web Solutions",
        description: "We provide the implementation services of feature development, migration and/or updating your codebase",
        steps:[
          {
            order: 1,
            title: 'Define',
            description: "Define objectives, scope, milestones and duration. Decide on language, framework and versions"
          },
          {
            order: 2,
            title: "Implement",
            description: "Experienced developers implement the migration and/or update on your codebase, implementing cost saving features such as continuous integration and continuous delivery"
          }
        ]
      },
    ]
  },
  getters:{
    getServices(state){
      return state.services
    },
    getService(state){
      return state.service
    },
  },
  actions:{
    fetchService({state, commit}, slug){
      state.services.filter(service => {
        if(service.slug == slug){
          commit('setService', service)
        }
      })
    }
  },
  mutations: {
    setService(state, newServiceState){
      state.service = newServiceState
    }
  },
}