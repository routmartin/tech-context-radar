<template>
    <Head title="Settings" />
    <RadarAppShell current="settings" :today-count="activeInterestCount">
        <form @submit.prevent="submit">
            <section class="page-hero">
                <div>
                    <span class="eyebrow">Configuration</span>
                    <h1 id="page-title">Settings</h1>
                    <p class="lead">Tune your radar so it only shows what matters.</p>
                </div>
                <aside class="hero-summary" aria-label="Settings summary">
                    <span class="panel-kicker">Your radar profile</span>
                    <h2>{{ form.briefing_length_minutes }} min brief, {{ thresholdLabel }}, {{ activeInterestCount }} active interests.</h2>
                    <div class="summary-grid">
                        <div class="summary-cell"><span class="metric-label">Active interests</span><strong class="metric-value">{{ activeInterestCount }}</strong></div>
                        <div class="summary-cell"><span class="metric-label">Noise threshold</span><strong class="metric-value">{{ thresholdLabel }}</strong></div>
                    </div>
                </aside>
            </section>

            <div class="settings-grid">
                <div class="settings-stack">
                    <section class="glass-panel setting-panel" aria-label="Profile settings">
                        <div class="panel-head"><div><span class="panel-kicker">Profile</span><h2>Your identity on the radar.</h2></div></div>
                        <div class="setting-section">
                            <div class="profile-wrap">
                                <div class="avatar" aria-hidden="true">{{ initials }}</div>
                                <div class="avatar-fields">
                                    <div class="field-row">
                                        <label class="field-label" for="profile-name">Name</label>
                                        <input id="profile-name" class="field-input" type="text" :value="page.props.auth.user?.name ?? ''" readonly />
                                    </div>
                                    <div class="field-row">
                                        <label class="field-label" for="profile-email">Email</label>
                                        <input id="profile-email" class="field-input" type="email" :value="page.props.auth.user?.email ?? ''" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="glass-panel setting-panel" aria-label="Briefing preferences">
                        <div class="panel-head">
                            <div><span class="panel-kicker">Briefing preferences</span><h2>Control depth, threshold, and scope.</h2></div>
                            <button class="primary-action setting-save" type="submit" :disabled="form.processing">Apply</button>
                        </div>
                        <div class="setting-section">
                            <h3>Briefing length</h3>
                            <p>How much time each daily brief should fill.</p>
                            <div class="segmented" role="radiogroup" aria-label="Briefing length">
                                <button
                                    v-for="option in briefOptions"
                                    :key="option.value"
                                    type="button"
                                    role="radio"
                                    :aria-checked="form.briefing_length_minutes === option.value"
                                    @click="form.briefing_length_minutes = option.value"
                                >
                                    {{ option.label }}
                                </button>
                            </div>
                        </div>
                        <div class="setting-section">
                            <h3>Priority threshold</h3>
                            <p>How aggressive the agent is when filtering noise.</p>
                            <div class="slider-wrap">
                                <input id="priority-threshold" v-model.number="form.priority_threshold" class="range" type="range" min="50" max="90" step="10" aria-label="Priority threshold" />
                                <div class="slider-labels">
                                    <span v-for="label in thresholdSteps" :key="label" :class="{ 'is-active': label === thresholdLabel }">{{ label }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="setting-section" style="border-bottom:0;">
                            <h3>Preferred categories</h3>
                            <p>Topics that always get priority placement in your brief.</p>
                            <div class="category-chip-row">
                                <template v-for="group in categoryGroups" :key="group.label">
                                    <span class="chip-group-label">{{ group.label }}</span>
                                    <button
                                        v-for="category in group.categories"
                                        :key="category.id"
                                        class="category-chip"
                                        type="button"
                                        :aria-pressed="form.preferred_categories.includes(category.name)"
                                        @click="toggleCategory(category.name)"
                                    >
                                        {{ category.name }}
                                    </button>
                                </template>
                            </div>
                        </div>
                    </section>

                    <section class="glass-panel setting-panel" aria-label="Notifications">
                        <div class="panel-head"><div><span class="panel-kicker">Notifications</span><h2>Stay informed without the noise.</h2></div></div>
                        <div class="setting-section">
                            <div class="toggle-row"><div><h3>Daily briefing reminder</h3><p>A push notification when your daily radar is ready.</p></div><button class="switch" type="button" :aria-pressed="form.daily_reminder_enabled" @click="form.daily_reminder_enabled = !form.daily_reminder_enabled"><span></span></button></div>
                            <div class="toggle-row"><div><h3>Priority signal alerts</h3><p>Interrupt for high-impact signals during the day.</p></div><button class="switch" type="button" :aria-pressed="form.priority_alerts_enabled" @click="form.priority_alerts_enabled = !form.priority_alerts_enabled"><span></span></button></div>
                            <div class="toggle-row" style="border-bottom:0;padding-bottom:0;"><div><h3>Weekly summary</h3><p>A weekend digest of the week's most important shifts.</p></div><button class="switch" type="button" :aria-pressed="form.weekly_summary_enabled" @click="form.weekly_summary_enabled = !form.weekly_summary_enabled"><span></span></button></div>
                        </div>
                    </section>
                </div>

                <aside class="settings-stack side-panel">
                    <section class="glass-panel setting-panel" aria-label="Radar preferences">
                        <div class="panel-head"><div><span class="panel-kicker">Radar topics</span><h2>Domains the agent watches.</h2></div></div>
                        <div class="setting-section">
                            <template v-for="(group, gi) in categoryGroups" :key="group.label">
                                <div class="toggle-group-head" :class="gi > 0 ? 'toggle-group-head-offset' : ''">{{ group.label }}</div>
                                <div v-for="category in group.categories" :key="category.id" class="toggle-row" :style="gi === categoryGroups.length - 1 && category === group.categories[group.categories.length - 1] ? 'border-bottom:0;padding-bottom:0;' : undefined">
                                    <div>
                                        <h3>{{ category.name }}</h3>
                                        <p>{{ categoryDescription(category.name) }}</p>
                                    </div>
                                    <button class="switch" type="button" :aria-pressed="form.preferred_categories.includes(category.name)" @click="toggleCategory(category.name)"><span></span></button>
                                </div>
                            </template>
                        </div>
                    </section>

                    <section class="glass-panel setting-panel" aria-label="Appearance">
                        <div class="panel-head"><div><span class="panel-kicker">Appearance</span><h2>How the radar looks.</h2></div></div>
                        <div class="setting-section">
                            <div class="toggle-row"><div><h3>Dark mode</h3><p>Premium dark theme optimized for scanning.</p></div><button class="switch" type="button" :aria-pressed="form.dark_mode_enabled" @click="form.dark_mode_enabled = !form.dark_mode_enabled"><span></span></button></div>
                            <div class="toggle-row" style="border-bottom:0;padding-bottom:0;"><div><h3>Compact mode</h3><p>Tighter spacing for more signals per screen.</p></div><button class="switch" type="button" :aria-pressed="form.compact_mode_enabled" @click="form.compact_mode_enabled = !form.compact_mode_enabled"><span></span></button></div>
                        </div>
                    </section>

                    <section class="glass-panel setting-panel" aria-label="Account">
                        <div class="panel-head"><div><span class="panel-kicker">Account</span><h2>Manage your data.</h2></div></div>
                        <div class="setting-section">
                            <div class="action-group">
                                <Link class="secondary-action" href="/profile">Change password</Link>
                                <button class="secondary-action" type="submit">Save preferences</button>
                                <Link class="danger-action full-action" href="/profile">Account actions</Link>
                            </div>
                        </div>
                    </section>
                </aside>
            </div>
        </form>
    </RadarAppShell>
</template>

<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import RadarAppShell from '../Components/RadarAppShell.vue';
import type { Category, Preference, SharedPageProps } from '../types';

const props = defineProps<{
    categories: Category[];
    preference: Preference;
}>();

const page = usePage<SharedPageProps>();
const form = useForm({ ...props.preference });
const briefOptions = [
    { value: 5, label: '5 min' },
    { value: 10, label: '10 min' },
    { value: 15, label: '15 min' },
    { value: 20, label: 'Deep brief' },
];
const thresholdSteps = ['Relaxed', 'Moderate', 'Balanced', 'Strict', 'Maximum'];
const thresholdLabel = computed(() => thresholdSteps[Math.min(4, Math.max(0, Math.round((form.priority_threshold - 50) / 10)))]);
const activeInterestCount = computed(() => form.preferred_categories.length);
const initials = computed(() =>
    (page.props.auth.user?.name ?? 'TR')
        .split(' ')
        .slice(0, 2)
        .map((part) => part[0]?.toUpperCase() ?? '')
        .join(''),
);

const groupDefs = [
    { label: 'Core AI', names: ['AI'] },
    { label: 'AI Ecosystem', names: ['DevTools', 'Frameworks', 'Cloud'] },
    { label: 'Other', names: ['Security', 'Blogs'] },
];

const categoryGroups = computed(() =>
    groupDefs
        .map((group) => ({
            label: group.label,
            categories: props.categories.filter((c) => group.names.includes(c.name)),
        }))
        .filter((group) => group.categories.length > 0),
);

function toggleCategory(category: string) {
    if (form.preferred_categories.includes(category)) {
        form.preferred_categories = form.preferred_categories.filter((item) => item !== category);
        return;
    }

    form.preferred_categories = [...form.preferred_categories, category];
}

function categoryDescription(category: string) {
    const descriptions: Record<string, string> = {
        AI: 'Models, agents, research breakthroughs',
        DevTools: 'Agentic coding, MCP, AI editor workflows',
        Frameworks: 'LangChain, LlamaIndex, AI SDKs',
        Cloud: 'GPU infra, model hosting, Vertex AI, Bedrock',
        Security: 'Vulnerabilities, compliance, supply chain',
        Blogs: 'High-signal updates for this domain',
    };

    return descriptions[category] ?? 'High-signal updates for this domain';
}

function submit() {
    form.put('/settings/preferences', { preserveScroll: true });
}
</script>

<style>
.settings-grid { display: grid; grid-template-columns: minmax(0, 1fr) 360px; gap: 18px; align-items: start; }
.settings-stack { display: grid; gap: 18px; }
.setting-panel { padding: 0; overflow: hidden; border-radius: var(--radius-lg); }
.setting-panel .panel-head { padding: clamp(18px, 2.4vw, 26px); margin-bottom: 0; border-bottom: 1px solid var(--border); }
.setting-section { padding: clamp(16px, 2.2vw, 22px) clamp(18px, 2.4vw, 26px); border-top: 1px solid var(--border); }
.setting-section:first-of-type { border-top: 0; }
.setting-section h3 { margin-bottom: 6px; font-size: 15px; font-weight: 720; letter-spacing: -0.006em; }
.setting-section > p { margin-bottom: 0; font-size: 13px; color: var(--muted); }
.setting-section > p + * { margin-top: 14px; }
.profile-wrap { display: grid; grid-template-columns: auto 1fr; gap: 20px; align-items: center; }
.avatar {
    width: 64px; height: 64px; display: grid; place-items: center; flex: 0 0 auto; border: 1px solid var(--border-strong);
    border-radius: 50%; background: radial-gradient(circle at 35% 25%, rgba(156,255,183,0.46), transparent 30%), rgba(255,255,255,0.065);
    box-shadow: var(--glow); color: var(--accent); font-size: 22px; font-weight: 780; letter-spacing: -0.01em;
}
.avatar-fields { display: grid; gap: 12px; }
.field-row { display: grid; gap: 6px; }
.field-label { color: var(--muted); font-size: 12px; font-weight: 650; letter-spacing: 0.02em; }
.field-input {
    width: 100%; min-height: 44px; padding: 0 14px; border: 1px solid var(--border); border-radius: var(--radius-md); background: rgba(255,255,255,0.045);
    color: var(--fg); font-size: 14px;
}
.segmented { display: inline-flex; gap: 4px; padding: 4px; border: 1px solid var(--border); border-radius: var(--radius-pill); background: rgba(255,255,255,0.045); }
.segmented button {
    min-height: 38px; padding: 0 18px; border: 0; border-radius: var(--radius-pill); background: transparent; color: var(--muted);
    font-size: 13px; font-weight: 640; cursor: pointer;
}
.segmented button[aria-checked="true"] { background: var(--accent); color: var(--accent-on); box-shadow: 0 8px 28px rgba(92,255,141,0.2); }
.slider-wrap { display: grid; gap: 12px; padding: 8px 0 4px; }
.slider-labels { display: flex; justify-content: space-between; color: var(--meta); font-size: 11px; font-weight: 600; letter-spacing: 0.01em; }
.slider-labels span.is-active { color: var(--accent); font-weight: 700; }
.range { width: 100%; accent-color: var(--accent); }
.chip-group-label { width: 100%; font-size: 10px; font-weight: 700; letter-spacing: 0.04em; text-transform: uppercase; color: var(--meta); padding-top: 4px; }
.chip-group-label:not(:first-child) { margin-top: 4px; }
.category-chip-row { display: flex; flex-wrap: wrap; gap: 8px; }
.category-chip {
    min-height: 34px; display: inline-flex; align-items: center; gap: 8px; padding: 0 13px; border: 1px solid var(--border); border-radius: var(--radius-pill);
    background: rgba(255,255,255,0.045); color: var(--muted); font-size: 13px; font-weight: 640; cursor: pointer;
}
.category-chip[aria-pressed="true"] { border-color: transparent; background: rgba(156,255,183,0.14); color: var(--accent-strong); box-shadow: 0 0 24px rgba(92,255,141,0.12); }
.category-chip[aria-pressed="true"]::before {
    width: 6px; height: 6px; content: ""; border-radius: 50%; background: var(--accent-strong); box-shadow: 0 0 12px var(--accent-strong);
}
.category-chip::before { width: 6px; height: 6px; content: ""; border-radius: 50%; background: var(--border); }
.danger-action {
    min-height: 44px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 0 16px; border: 1px solid rgba(255,139,154,0.3);
    border-radius: var(--radius-pill); background: rgba(255,139,154,0.08); color: var(--danger); font-size: 13px; font-weight: 700;
}
.action-group { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.action-group .secondary-action, .action-group .danger-action { width: 100%; }
.full-action { grid-column: 1 / -1; }
.setting-save { min-height: 44px; padding: 0 18px; font-size: 13px; }
.toggle-group-head { font-size: 11px; font-weight: 700; letter-spacing: 0.04em; text-transform: uppercase; color: var(--meta); padding: 12px 0 4px; }
.toggle-group-head-offset { margin-top: 8px; border-top: 1px solid var(--border); padding-top: 20px; }
.toggle-row { display: flex; align-items: center; justify-content: space-between; gap: 18px; padding: 16px 0; border-top: 1px solid var(--border); }
.toggle-row:first-child { border-top: 0; padding-top: 0; }
.toggle-row h3 { margin-bottom: 4px; }
.toggle-row p { margin-bottom: 0; color: var(--muted); font-size: 13px; line-height: 1.45; }
.switch {
    width: 54px; height: 32px; display: inline-flex; align-items: center; flex: 0 0 auto; padding: 3px; border: 1px solid var(--border);
    border-radius: var(--radius-pill); background: rgba(255,255,255,0.07); cursor: pointer;
}
.switch span { width: 24px; height: 24px; border-radius: 50%; background: var(--muted); transition: transform var(--base) var(--ease), background var(--base) var(--ease); }
.switch[aria-pressed="true"] { background: rgba(156,255,183,0.16); border-color: var(--border-strong); }
.switch[aria-pressed="true"] span { background: var(--accent); transform: translateX(20px); }
.summary-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; margin-top: 18px; }
.summary-cell { min-height: 88px; padding: 15px; border: 1px solid var(--border); border-radius: 22px; background: rgba(2,4,3,0.34); }
@media (max-width: 960px) {
    .settings-grid { grid-template-columns: 1fr; }
    .action-group { grid-template-columns: 1fr; }
    .profile-wrap { grid-template-columns: 1fr; justify-items: center; text-align: center; }
    .segmented { width: 100%; justify-content: center; }
    .segmented button { flex: 1; text-align: center; }
    .avatar { width: 56px; height: 56px; font-size: 20px; }
}
@media (max-width: 420px) {
    .setting-section { padding: 14px 16px; }
    .category-chip-row { gap: 6px; }
    .category-chip { min-height: 30px; font-size: 12px; padding: 0 11px; }
}
</style>
