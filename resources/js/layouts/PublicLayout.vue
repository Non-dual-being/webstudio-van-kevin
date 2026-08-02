<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';
</script>

<template>
    <div class="flex min-h-screen flex-col bg-white text-neutral-900">
        <header class="border-b border-neutral-200">
            <div
                class="mx-auto flex w-full max-w-5xl flex-col gap-4 px-5 py-5 sm:flex-row sm:items-center sm:justify-between"
            >
                <Link
                    :href="home()"
                    class="w-fit text-lg font-semibold focus-visible:rounded-sm focus-visible:outline-2 focus-visible:outline-offset-4"
                >
                    Kevin Webstudio
                </Link>

                <nav aria-label="Hoofdnavigatie">
                    <ul class="flex flex-wrap gap-x-5 gap-y-3 text-sm">
                        <li>
                            <Link
                                href="/werk"
                                class="rounded-sm underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-offset-4"
                            >
                                Werk
                            </Link>
                        </li>
                        <li>
                            <Link
                                href="/diensten/websites"
                                class="rounded-sm underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-offset-4"
                            >
                                Diensten
                            </Link>
                        </li>
                        <li>
                            <Link
                                href="/over-mij"
                                class="rounded-sm underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-offset-4"
                            >
                                Over mij
                            </Link>
                        </li>
                        <li>
                            <Link
                                href="/contact"
                                class="rounded-sm underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-offset-4"
                            >
                                Contact
                            </Link>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>

        <main class="mx-auto w-full max-w-5xl flex-1 px-5 py-16 sm:py-24">
            <slot />
        </main>

        <footer class="border-t border-neutral-200">
            <div
                class="mx-auto flex w-full max-w-5xl flex-col gap-3 px-5 py-6 text-sm text-neutral-600 sm:flex-row sm:items-center sm:justify-between"
            >
                <p>&copy; Kevin Webstudio</p>
                <Link
                    href="/privacy"
                    class="w-fit rounded-sm underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-offset-4"
                >
                    Privacy
                </Link>
            </div>
        </footer>
    </div>
</template>
