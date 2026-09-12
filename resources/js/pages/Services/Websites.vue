<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';
</script>

<template>
    <Head title="Maatwerk websites" />
    <PublicLayout>
        <article class="max-w-3xl">
            <span
                aria-hidden="true"
                class="block h-1 w-10 rounded-full bg-amber-600"
            />
            <h1
                class="mt-5 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl"
            >
                Maatwerk websites
            </h1>
            <p class="mt-6 text-lg leading-8 text-slate-700">
                Ik bouw toegankelijke websites waarin jouw inhoud helder naar
                voren komt. We kijken samen naar bezoekers, beheer en
                toekomstige groei, zodat de techniek aansluit op wat je
                werkelijk nodig hebt.
            </p>
            <section
                aria-labelledby="werkwijze"
                class="mt-10 border-l-2 border-amber-600 pl-5"
            >
                <h2 id="werkwijze" class="text-lg font-semibold">
                    Eerst begrijpen, dan bouwen
                </h2>
                <p class="mt-2 leading-7 text-slate-600">
                    Ik maak keuzes graag inzichtelijk: van contentstructuur en
                    toegankelijkheid tot de onderdelen die je later zelf wilt
                    beheren.
                </p>
            </section>
        </article>
    </PublicLayout>
</template>
