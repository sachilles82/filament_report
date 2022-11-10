import * as Turbo from '@hotwired/turbo';

if (typeof window.Livewire === 'undefined') {
    throw 'Livewire Turbolinks Plugin: window.Livewire is undefined. Make sure @livewireScripts is placed above this script include'
}

let firstTime = true;

function wireTurboAfterFirstVisit () {
    if  (firstTime) {
        firstTime = false

        return
    }

    window.Livewire.restart()

    window.Alpine && window.Alpine.flushAndStopDeferringMutations && window.Alpine.flushAndStopDeferringMutations()
}

function wireTurboBeforeCache() {
    document.querySelectorAll('[wire\\:id]').forEach(function(el) {
        const component = el.__livewire;
        const dataObject = {
            fingerprint: component.fingerprint,
            serverMemo: component.serverMemo,
            effects: component.effects,
        };
        el.setAttribute('wire:initial-data', JSON.stringify(dataObject));
    });

    window.Alpine && window.Alpine.deferMutations && window.Alpine.deferMutations()
}

document.addEventListener("turbo:load", wireTurboAfterFirstVisit)
document.addEventListener("turbo:before-cache", wireTurboBeforeCache);

document.addEventListener("turbolinks:load", wireTurboAfterFirstVisit)
document.addEventListener("turbolinks:before-cache", wireTurboBeforeCache);

Livewire.hook('beforePushState', (state) => {
    if (! state.turbolinks) state.turbolinks = {}
})

Livewire.hook('beforeReplaceState', (state) => {
    if (! state.turbolinks) state.turbolinks = {}
})
function initAlpineTurboPermanentFix() {
    document.addEventListener('turbo:before-render', () => {
        let permanents = document.querySelectorAll('[data-turbo-permanent]');
        let undos = Array.from(permanents).map(el => {
            el._x_ignore = true;
            return () => {
                delete el._x_ignore;
            };
        });

        document.addEventListener('turbo:render', function handler() {
            while(undos.length) undos.shift()();
            document.removeEventListener('turbo:render', handler);
        });
    });
}

if (window.Alpine !== undefined) {
    initAlpineTurboPermanentFix();
}

export default Turbo;
