export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string | null;
}

export interface Category {
    id: number;
    name: string;
    slug: string;
}

export interface Briefing {
    title: string;
    summary: string;
    date: string;
    readingTime: number;
    prioritySignals: number;
    lowImpactFiltered: number;
    confidenceScore: number;
}

export interface Signal {
    id: number;
    title: string;
    slug: string;
    summary: string;
    whyItMatters: string;
    developerImpact: string;
    recommendedAction: string;
    priorityScore: number;
    readTimeMinutes: number;
    sourceCount: number;
    publishedAt: string | null;
    publishedDate: string | null;
    category: {
        name: string;
        slug: string;
    };
    source: {
        name: string;
        slug: string;
        trustLevel: string;
    } | null;
    isSaved: boolean;
    isRead: boolean;
    url: string;
}

export interface Source {
    id: number;
    name: string;
    slug: string;
    trustLevel: string;
    updatesToday: number;
    lastScanned: string | null;
    status: string;
    category: {
        name: string;
        slug: string;
    };
    recentSignals: Array<{
        title: string;
        slug: string;
        priorityScore: number;
        url: string;
    }>;
    noiseFiltered: number;
}

export interface Preference {
    briefing_length_minutes: number;
    priority_threshold: number;
    preferred_categories: string[];
    daily_reminder_enabled: boolean;
    priority_alerts_enabled: boolean;
    weekly_summary_enabled: boolean;
    compact_mode_enabled: boolean;
    dark_mode_enabled: boolean;
}

export interface SharedPageProps {
    [key: string]: unknown;
    auth: {
        user: User | null;
    };
    flash: {
        toast?: string | null;
    };
}
