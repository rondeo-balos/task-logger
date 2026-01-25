<script setup>
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowRightIcon,
    CheckIcon,
    PlayIcon,
    SparklesIcon,
    StarIcon,
    ShieldCheckIcon,
    ChatBubbleLeftRightIcon,
} from '@heroicons/vue/24/solid';
import { computed, ref } from 'vue';

const features = [
    { title: 'Board-aware timers', body: 'Start/stop from any board card; logs carry status, assignees, and rich context automatically.' },
    { title: 'Rich task narratives', body: 'Add multi-note, rich-text descriptions - no more mystery time entries.' },
    { title: 'Granular access', body: 'Owner/read/write/view-others permissions keep data least-privileged by default.' },
    { title: 'Filters & exports', body: 'Weekly/monthly rollups by user/date in one click. Export to CSV in seconds.' },
    { title: 'Attachments on boards', body: 'Files, links, and screenshots stay with the work, not lost in chat.' },
    { title: 'Zero-cost core', body: 'Time tracking, boards, rich notes, and permissions are free. No per-seat surprises.' },
];

const solutions = [
    { title: 'Social task feeds', body: 'Capture lightweight updates with context-rich notes and attachments.' },
    { title: 'Automated exports', body: 'Scheduled rollups for payroll, billing, or retros without spreadsheets.' },
    { title: 'SEO-friendly reporting', body: 'Readable, shareable summaries for stakeholders - no app login required.' },
];

const steps = [
    { title: 'Track, analyze, optimize', body: 'Start/stop timers from boards and see where effort lands instantly.' },
    { title: 'Insight. Action. Results.', body: 'Filter by user, board, or date to unblock teams and reassign work fast.' },
    { title: 'Monitor, measure, improve', body: 'History and exports make audits and retros effortless.' },
];

const testimonials = [
    { name: 'Casey L.', role: 'Ops Lead, Cloud tools', quote: 'Finally a timer that understands boards. Our weekly reports went from hours to minutes.', rating: 5 },
    { name: 'Ramon K.', role: 'Engineering Manager', quote: 'Board-aware logging killed orphaned time entries. Audits are straightforward now.', rating: 5 },
    { name: 'Priya S.', role: 'Product Lead', quote: 'Rich descriptions + permissions = context stays clean and secure.', rating: 5 },
];

const pricingTiers = [
    { name: 'Starter', price: 0, period: 'mo', bullets: ['Core time tracking', 'Board-linked logging', 'Rich task descriptions', 'Filters & exports'] },
    { name: 'Pro', price: 9, period: 'mo', bullets: ['Advanced exports', 'Priority support', 'Upcoming automations', 'More storage for attachments'] },
    { name: 'Team', price: 0, period: 'mo', bullets: ['Granular access control', 'All core features', 'Unlimited workspaces', 'Best for squads'] },
];

const pricingMode = ref('monthly');
const pricingLabel = computed(() => pricingMode.value === 'yearly' ? 'Yearly (save 15%)' : 'Monthly');
</script>

<template>
    <Head>
        <title>Task Logger | Time Tracking Meets Boards</title>
        <meta name="description" content="Task Logger blends board-aware time tracking, rich task context, and granular permissions in a fast, privacy-friendly app." />
    </Head>

    <div class="min-h-screen bg-[#0a0f1d] text-white">
        <!-- Hero -->
        <header class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-[#057904] via-[#008400] to-[#0f3d1c]"></div>
            <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top_left,#ffffff44_0%,transparent_35%)]"></div>
            <div class="relative mx-auto max-w-6xl px-4 py-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="size-14 rounded-xl flex items-center justify-center font-black text-lg">
                        <img src="/logo.svg" class="size-12" style="filter: brightness(0) saturate(100%) invert(100%) sepia(0%) saturate(7500%) hue-rotate(140deg) brightness(108%) contrast(102%);" />
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-widest text-white/80">Task Logger</p>
                        <p class="text-sm text-white/80">Time tracking + Boards</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center gap-5 text-sm">
                    <a href="#features" class="hover:text-amber-200">Features</a>
                    <a href="#solutions" class="hover:text-amber-200">Solutions</a>
                    <a href="#pricing" class="hover:text-amber-200">Pricing</a>
                    <a href="#testimonials" class="hover:text-amber-200">Stories</a>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <Link :href="route('login')" class="px-3 py-2 rounded-lg bg-white/10 hover:bg-white/20 border border-white/20">Log in</Link>
                    <Link :href="route('register')" class="px-4 py-2 rounded-lg bg-white text-[#0f172a] font-semibold shadow-lg hover:bg-amber-100">Get started</Link>
                </div>
            </div>

            <div class="relative mx-auto max-w-6xl px-4 pb-14 pt-6 text-center">
                <p class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 text-sm font-semibold">
                    Powerful tracking meets seamless design
                </p>
                <h1 class="mt-4 text-4xl md:text-5xl font-black leading-tight">Board-aware time tracking with rich context.</h1>
                <p class="mt-3 text-lg text-white/80 max-w-3xl mx-auto">
                    Timers that live where the work lives. Capture descriptions, attachments, and permissions in one place - export-ready from day one.
                </p>
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <Link :href="route('register')" class="px-5 py-3 rounded-full bg-white text-[#0f172a] font-semibold shadow-lg hover:bg-amber-100 flex items-center gap-2">
                        Get started free <ArrowRightIcon class="size-4" />
                    </Link>
                    <Link :href="route('login')" class="px-5 py-3 rounded-full bg-transparent border border-white/40 text-white hover:bg-white/10 flex items-center gap-2">
                        View demo <PlayIcon class="size-4" />
                    </Link>
                </div>
                <div class="mt-10 bg-white rounded-3xl shadow-2xl p-6 border border-white/60">
                    <div class="grid md:grid-cols-3 gap-4 text-[#0f172a]">
                        <div class="rounded-2xl bg-[#f5f3ff] p-4 shadow-sm">
                            <p class="text-xs text-gray-600">Time logged</p>
                            <p class="text-2xl font-bold">182h</p>
                            <p class="text-xs text-emerald-600">+12h this week</p>
                        </div>
                        <div class="rounded-2xl bg-[#f0f9ff] p-4 shadow-sm">
                            <p class="text-xs text-gray-600">Active boards</p>
                            <p class="text-2xl font-bold">7</p>
                            <p class="text-xs text-blue-600">Across 3 teams</p>
                        </div>
                        <div class="rounded-2xl bg-[#fff7ed] p-4 shadow-sm">
                            <p class="text-xs text-gray-600">Exports</p>
                            <p class="text-2xl font-bold">Weekly</p>
                            <p class="text-xs text-amber-600">CSV & PDF ready</p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 text-sm text-white/80 flex flex-wrap justify-center gap-6">
                    <span class="flex items-center gap-1"><CheckIcon class="size-4 text-emerald-300" /> No credit card</span>
                    <span class="flex items-center gap-1"><CheckIcon class="size-4 text-emerald-300" /> Privacy-friendly</span>
                    <span class="flex items-center gap-1"><CheckIcon class="size-4 text-emerald-300" /> Exports included</span>
                </div>
            </div>
        </header>

        <!-- Logos -->
        <section class="bg-white text-[#0f172a] py-10">
            <div class="mx-auto max-w-6xl px-4 text-center">
                <p class="text-sm uppercase tracking-widest text-gray-500">
                    Clarity-first time tracking for modern teams
                </p>
                <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-4 text-sm font-semibold text-gray-500">
                    <span>Dropbox</span>
                    <span>Airtable</span>
                    <span>Razorpay</span>
                    <span>Contentful</span>
                    <span>Culture AI</span>
                </div>
            </div>
        </section>

        <!-- About -->
        <section id="solutions" class="bg-white text-[#0f172a] py-14">
            <div class="mx-auto max-w-6xl px-4 grid lg:grid-cols-2 gap-10 items-center">
                <div class="space-y-3">
                    <p class="text-xs uppercase tracking-widest text-emerald-600">About us</p>
                    <h2 class="text-3xl font-bold">Where task logging gets easier for everyone.</h2>
                    <p class="text-gray-600">Task Logger unifies time tracking, boards, descriptions, and permissions. No more siloed timers - just clean, export-ready logs that respect your workflows.</p>
                    <div class="grid grid-cols-3 gap-3 text-center">
                        <div class="rounded-2xl bg-emerald-50 p-3">
                            <p class="text-xl font-bold text-emerald-700">200k+</p>
                            <p class="text-xs text-gray-500">Workspaces</p>
                        </div>
                        <div class="rounded-2xl bg-emerald-50 p-3">
                            <p class="text-xl font-bold text-emerald-700">205k</p>
                            <p class="text-xs text-gray-500">Daily tasks logged</p>
                        </div>
                        <div class="rounded-2xl bg-emerald-50 p-3">
                            <p class="text-xl font-bold text-emerald-700">2.5h</p>
                            <p class="text-xs text-gray-500">Avg. time saved/week</p>
                        </div>
                    </div>
                    <Link :href="route('register')" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-emerald-600 text-white font-semibold shadow hover:bg-emerald-500">
                        Discover more <ArrowRightIcon class="size-4" />
                    </Link>
                </div>
                <div class="rounded-3xl border border-gray-100 shadow-xl p-6 bg-gradient-to-br from-white to-emerald-50">
                    <div class="flex gap-3 items-start">
                        <div class="size-12 rounded-xl flex items-center justify-center font-bold">
                            <img src="/logo.svg" class="size-12" />
                        </div>
                        <div>
                            <p class="font-semibold">Board-aware timers</p>
                            <p class="text-sm text-gray-600">Keep status, assignees, and time aligned - no orphaned logs.</p>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                        <div class="rounded-xl bg-white border border-gray-100 p-3">
                            <p class="text-gray-600">Pending</p>
                            <p class="font-bold text-emerald-700">3 cards</p>
                        </div>
                        <div class="rounded-xl bg-white border border-gray-100 p-3">
                            <p class="text-gray-600">In Progress</p>
                            <p class="font-bold text-emerald-700">12h logged</p>
                        </div>
                        <div class="rounded-xl bg-white border border-gray-100 p-3">
                            <p class="text-gray-600">To Review</p>
                            <p class="font-bold text-emerald-700">4 cards</p>
                        </div>
                        <div class="rounded-xl bg-white border border-gray-100 p-3">
                            <p class="text-gray-600">Completed</p>
                            <p class="font-bold text-emerald-700">34 tasks</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Solutions -->
        <section class="bg-[#f8fafc] text-[#0f172a]" id="features">
            <div class="mx-auto max-w-6xl px-4 py-14 space-y-8">
                <div class="text-center space-y-2">
                    <p class="text-xs uppercase tracking-widest text-emerald-600">What we offer</p>
                    <h2 class="text-3xl font-bold">Our solutions, built-in.</h2>
                </div>
                <div class="grid md:grid-cols-3 gap-4">
                    <div v-for="item in solutions" :key="item.title" class="rounded-2xl bg-white shadow p-5 border border-gray-100">
                        <div class="size-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                            <SparklesIcon class="size-5" />
                        </div>
                        <h3 class="mt-3 text-lg font-semibold">{{ item.title }}</h3>
                        <p class="text-sm text-gray-600 mt-2">{{ item.body }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How it works -->
        <section class="bg-[#0b0f1d] text-white">
            <div class="mx-auto max-w-6xl px-4 py-14">
                <div class="text-center space-y-2 mb-8">
                    <p class="text-xs uppercase tracking-widest text-emerald-300">How it works</p>
                    <h2 class="text-3xl font-bold">Marketing made simple - now for tasks.</h2>
                </div>
                <div class="grid md:grid-cols-3 gap-4">
                    <div v-for="(step, idx) in steps" :key="step.title" class="rounded-2xl bg-gradient-to-br from-[#111827] to-[#0b1020] border border-white/10 p-5">
                        <div class="text-sm text-emerald-300 mb-2">Step {{ idx + 1 }}</div>
                        <h3 class="text-lg font-semibold">{{ step.title }}</h3>
                        <p class="text-sm text-gray-300 mt-2">{{ step.body }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing -->
        <section id="pricing" class="bg-white text-[#0f172a]">
            <div class="mx-auto max-w-6xl px-4 py-14 space-y-8">
                <div class="text-center space-y-2">
                    <p class="text-xs uppercase tracking-widest text-emerald-600">Pricing</p>
                    <h2 class="text-3xl font-bold">Pricing that fits your needs</h2>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold">
                        <span>{{ pricingLabel }}</span>
                        <label class="inline-flex items-center gap-1 text-gray-500 text-xs">
                            <input type="checkbox" v-model="pricingMode" true-value="yearly" false-value="monthly" class="accent-emerald-600" />
                            <span>Yearly discount</span>
                        </label>
                    </div>
                </div>
                <div class="grid md:grid-cols-3 gap-4">
                    <div v-for="tier in pricingTiers" :key="tier.name" class="rounded-2xl border border-gray-100 bg-white shadow-sm p-6 hover:-translate-y-1 hover:shadow-lg transition">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold">{{ tier.name }}</h3>
                            <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">Most popular</span>
                        </div>
                        <div class="mt-3 flex items-baseline gap-1">
                            <span class="text-3xl font-bold">${{ tier.price }}</span>
                            <span class="text-sm text-gray-500">/ {{ tier.period }}</span>
                        </div>
                        <ul class="mt-4 space-y-2 text-sm text-gray-700">
                            <li v-for="item in tier.bullets" :key="item" class="flex items-start gap-2">
                                <CheckIcon class="size-4 text-emerald-500 mt-1" /> <span>{{ item }}</span>
                            </li>
                        </ul>
                        <Link :href="route('register')" class="mt-5 inline-flex w-full items-center justify-center rounded-full bg-emerald-600 text-white font-semibold py-2 hover:bg-emerald-500">
                            Start free
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section id="testimonials" class="bg-[#0b1120] text-white">
            <div class="mx-auto max-w-6xl px-4 py-14 space-y-6">
                <div class="text-center space-y-2">
                    <p class="text-xs uppercase tracking-widest text-emerald-300">Testimonials</p>
                    <h2 class="text-3xl font-bold">Success stories from our users</h2>
                </div>
                <div class="grid md:grid-cols-3 gap-4">
                    <div v-for="item in testimonials" :key="item.name" class="rounded-2xl border border-white/10 bg-gradient-to-br from-[#12192f] to-[#0c1326] p-5 shadow">
                        <div class="flex items-center gap-2">
                            <div class="size-10 rounded-full bg-white/10 flex items-center justify-center font-bold">{{ item.name[0] }}</div>
                            <div>
                                <p class="font-semibold">{{ item.name }}</p>
                                <p class="text-xs text-gray-400">{{ item.role }}</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-200 mt-3">{{ item.quote }}</p>
                        <div class="flex items-center gap-1 mt-3 text-amber-300">
                            <StarIcon v-for="i in item.rating" :key="i" class="size-4" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-[#057904] via-[#008400] to-[#0f3d1c]"></div>
            <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top_left,#ffffff44_0%,transparent_35%)]"></div>
            <div class="relative mx-auto max-w-6xl px-4 py-12 text-center space-y-4">
                <p class="text-sm uppercase tracking-widest text-white/80">Ready to get started?</p>
                <h2 class="text-3xl font-bold">Want time tracking that works for your team?</h2>
                <p class="text-white/80 max-w-2xl mx-auto">Board-aware timers, rich notes, exports, and permissions - built for fast-moving teams who care about clarity.</p>
                <div class="flex flex-wrap justify-center gap-3">
                    <Link :href="route('register')" class="px-5 py-3 rounded-full bg-white text-[#0f172a] font-semibold shadow-lg hover:bg-amber-100 flex items-center gap-2">
                        Start free <ArrowRightIcon class="size-4" />
                    </Link>
                    <Link href="mailto:hello@rondeobalos.com" class="px-5 py-3 rounded-full bg-transparent border border-white/40 text-white hover:bg-white/10 flex items-center gap-2">
                        Book a demo <ChatBubbleLeftRightIcon class="size-4" />
                    </Link>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-[#0b0f1d] text-gray-300">
            <div class="mx-auto max-w-6xl px-4 py-10 grid md:grid-cols-4 gap-6">
                <div class="space-y-2">
                    <div class="size-14 rounded-xl flex items-center justify-center font-black text-lg text-white">
                        <img src="/logo.svg" class="size-12" style="filter: brightness(0) saturate(100%) invert(100%) sepia(0%) saturate(7500%) hue-rotate(140deg) brightness(108%) contrast(102%);" />
                    </div>
                    <p class="text-sm text-gray-400">Task Logger is the board-aware timer with rich context and exports built-in.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-3">Quick links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#features" class="hover:text-white">Features</a></li>
                        <li><a href="#pricing" class="hover:text-white">Pricing</a></li>
                        <li><a href="#testimonials" class="hover:text-white">Stories</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-3">Company</h4>
                    <ul class="space-y-2 text-sm">
                        <li><Link :href="route('login')" class="hover:text-white">Login</Link></li>
                        <li><Link :href="route('register')" class="hover:text-white">Start free</Link></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-3">Ready to talk?</h4>
                    <p class="text-sm text-gray-400">We respond within one business day.</p>
                    <p class="text-sm text-gray-200 mt-2">hello@rondeobalos.com</p>
                </div>
            </div>
            <div class="border-t border-white/10 text-xs text-center py-4 text-gray-500">
                © Task Logger. Built for focused teams. No tracking scripts.
            </div>
        </footer>
    </div>
</template>
