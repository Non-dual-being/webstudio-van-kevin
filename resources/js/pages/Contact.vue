<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';

const conversationPoints = [
    {
        title: 'Doel',
        text: 'Wat moet de website of het systeem straks opleveren?',
    },
    {
        title: 'Gebruikers',
        text: 'Wie gaat ermee werken en wat hebben zij daarbij nodig?',
    },
    {
        title: 'Huidige knelpunten',
        text: 'Waar kost het proces nu onnodig tijd of ontstaat verwarring?',
    },
    {
        title: 'Planning',
        text: 'Is er een gewenste start, deadline of moment om rekening mee te houden?',
    },
];

const processSteps = [
    {
        title: 'Kennismaken',
        text: 'We bespreken je idee, gebruikers en wat er nu speelt.',
    },
    {
        title: 'Voorstel afstemmen',
        text: 'Je krijgt een helder voorstel voor aanpak, omvang en planning.',
    },
    {
        title: 'Bouwen en bijstellen',
        text: 'We maken de oplossing stap voor stap en scherpen die samen aan.',
    },
];
</script>

<template>
    <Head title="Contact opnemen" />
    <PublicLayout>
        <section
            aria-labelledby="contact-title"
            class="grid items-start gap-10 lg:grid-cols-[minmax(0,1.15fr)_minmax(20rem,0.85fr)] lg:gap-14"
        >
            <div class="max-w-2xl">
                <p class="text-sm font-semibold tracking-wide text-slate-600">
                    Contact
                </p>
                <h1
                    id="contact-title"
                    class="mt-3 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl"
                >
                    Vertel me wat je wilt maken.
                </h1>
                <div class="mt-6 space-y-4 text-lg leading-8 text-slate-700">
                    <p>
                        Misschien heb je een website nodig die je verhaal helder
                        vertelt, een dashboard dat overzicht brengt of een
                        praktisch systeem dat terugkerend werk eenvoudiger
                        maakt.
                    </p>
                    <p>
                        Ik luister naar wat je wilt bereiken en denk mee vanuit
                        de mensen die ermee gaan werken. Zo komen inhoud,
                        ontwerp en techniek samen in een oplossing die bij je
                        dagelijkse praktijk past.
                    </p>
                </div>
            </div>

            <aside
                aria-labelledby="gesprek-titel"
                class="rounded-xl border border-stone-200 bg-stone-100 p-6 sm:p-7"
            >
                <h2
                    id="gesprek-titel"
                    class="text-xl font-semibold tracking-tight text-slate-950"
                >
                    Dit helpt bij een eerste gesprek
                </h2>
                <dl class="mt-6 space-y-5">
                    <div
                        v-for="point in conversationPoints"
                        :key="point.title"
                        class="border-t border-stone-300 pt-5 first:border-t-0 first:pt-0"
                    >
                        <dt class="font-semibold text-slate-900">
                            {{ point.title }}
                        </dt>
                        <dd class="mt-1 leading-7 text-slate-600">
                            {{ point.text }}
                        </dd>
                    </div>
                </dl>
            </aside>
        </section>

        <section
            aria-labelledby="werkwijze-titel"
            class="mt-14 border-t border-stone-200 pt-10 sm:mt-16"
        >
            <h2
                id="werkwijze-titel"
                class="text-2xl font-semibold tracking-tight text-slate-950"
            >
                Van eerste gesprek naar een werkende oplossing
            </h2>
            <ol class="mt-7 grid gap-7 md:grid-cols-3 md:gap-8">
                <li
                    v-for="(step, index) in processSteps"
                    :key="step.title"
                    class="grid grid-cols-[2rem_1fr] gap-x-3"
                >
                    <span
                        aria-hidden="true"
                        class="flex size-8 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white"
                    >
                        {{ index + 1 }}
                    </span>
                    <div>
                        <h3 class="font-semibold text-slate-950">
                            {{ step.title }}
                        </h3>
                        <p class="mt-2 leading-7 text-slate-600">
                            {{ step.text }}
                        </p>
                    </div>
                </li>
            </ol>
        </section>
    </PublicLayout>
</template>
