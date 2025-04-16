import { defineStore } from 'pinia'

export const HoggarListing  = defineStore('listing', {
    state: () => ({ 
      settings: {}, 
      actionIds : [],
      groupActions : [],
    }),
    getters: {
     
    },
    actions: {

      resetActionIds() {
        this.actionIds = []
      },

      
      AddActionIds(a) {
        if (!this.actionIds.includes(a)) {
          this.actionIds.push(a);
        }
        console.log(this.actionIds);
      },

      RemoveActionIds(a) {
        const index = this.actionIds.indexOf(a);
          if (index !== -1) {
                 this.actionIds.splice(index, 1);
                }
      },


        setSettings(a) {
            this.settings = a
          },

    },
  }) 