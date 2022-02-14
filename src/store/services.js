export default {
  namespaced: true,
  state:{
    service: {},
    services:[
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
            description: "An expert will analysis your codebase, for complexity, code quality, convention and standards adherence and code style."
          },
          {
            order: 3,
            title: "Report",
            description: "A report will be issued, providing you with a calculated estimate of duration to achieve the goals set out in step 1"
          }
        ]
      },
      {
        slug: 'web-solutions',
        color: 'orange',
        title: "Web Solutions",
        description: "We provide feature development, codebase modernisation, version migration and architecture refactoring",
        steps:[
          {
            order: 1,
            title: 'Define',
            description: "Define objectives, scope, milestones and duration. Decide on language, framework and versions"
          },
          {
            order: 2,
            title: "Implement",
            description: "Experienced developers modernises a codebase using a wide array of automated refactoring tools"
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
