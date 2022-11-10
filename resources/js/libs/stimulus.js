import * as Stimulus from '@hotwired/stimulus';

if (typeof window.Livewire === 'undefined') {
    throw 'Livewire Stimulus Plugin: window.Livewire is undefined. Make sure @livewireScripts is placed above this script include'
}

let firstTime = true;

function wireStimulusAfterFirstVisit () {
    // We only want this handler to run AFTER the first load.
    if  (firstTime) {
        firstTime = false

        return
    }

    window.Livewire.restart()

    window.Alpine && window.Alpine.flushAndStopDeferringMutations && window.Alpine.flushAndStopDeferringMutations()
}

function wireStimulusBeforeCache() {
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

document.addEventListener("stimulus:load", wireStimulusAfterFirstVisit)
document.addEventListener("stimulus:before-cache", wireStimulusBeforeCache);

document.addEventListener("stimulus:load", wireStimulusAfterFirstVisit)
document.addEventListener("stimulus:before-cache", wireStimulusBeforeCache);

Livewire.hook('beforePushState', (state) => {
    if (! state.stimulus) state.stimulus = {}
})

Livewire.hook('beforeReplaceState', (state) => {
    if (! state.stimulus) state.stimulus = {}
})

function initAlpineStimulusPermanentFix() {
    document.addEventListener('stimulus:before-render', () => {
        let permanents = document.querySelectorAll('[data-stimulus-permanent]');
        let undos = Array.from(permanents).map(el => {
            el._x_ignore = true;
            return () => {
                delete el._x_ignore;
            };
        });

        document.addEventListener('stimulus:render', function handler() {
            while(undos.length) undos.shift()();
            document.removeEventListener('stimulus:render', handler);
        });
    });
}

if (window.Alpine !== undefined) {
    initAlpineStimulusPermanentFix();
}

export default Stimulus;
