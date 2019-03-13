import Vue from 'vue'
import Buefy from 'buefy'
import VueResource from 'vue-resource';

Vue.use(Buefy, {
    defaultInputHasCounter: false,
    defaultTooltipType: 'is-dark'
});
Vue.use(VueResource);
Vue.component('dashboard', require('./components/DashboardComponent.vue'));

Vue.http.headers.common['X-CSRF-TOKEN'] = document.head.querySelector('meta[name="csrf-token"]').content;

window.vue = new Vue({
    el: '#app',
    data: {
        // general
        'showNav': false,
        'modalActive': true,

        // create agreement
        'options': ['I accept the agreement']
    },
    methods: {
        // general
        closeModal() {
            this.modalActive = false;
        },

        // create agreement
        addOption() {
            this.options.push('');
            return false;
        },

        removeOption(index) {
            if (this.options.length <= 1) {
                return;
            }

            this.options.splice(index, 1);
            return false;
        }
    }
});

document.addEventListener('DOMContentLoaded', function(event) {
    const clipboard = new ClipboardJS('[data-clipboard-text]');
    clipboard.on('success', function(e) {
        const oldHTML = e.trigger.innerHTML;

        e.trigger.innerHTML = 'Copied';
        e.trigger.classList.add('is-static');
        setTimeout(function() {
            e.trigger.innerHTML = oldHTML;
            e.trigger.classList.remove('is-static');
        }, 2000);

        e.clearSelection();
    });
});
