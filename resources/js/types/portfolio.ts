export type ProjectText = {
    title: string;
    text: string;
};

export type PortfolioProject = {
    slug: string;
    title: string;
    category: string;
    summary: string;
    intro: string;
    challenge: string;
    solution: string;
    stepsTitle: string;
    featuresTitle: string;
    scopeTitle: string;
    projectUrl?: string;
    projectLinkLabel?: string;
    demoLinkLabel?: string;
    demoStartLabel?: string;
    demoUnavailableNote?: string;
    features: ProjectText[];
    steps: ProjectText[];
    images: {
        src: string;
        mobileSrc: string;
        mobileWidth: number;
        mobileHeight: number;
        width: number;
        height: number;
        alt: string;
        caption: string;
    }[];
    trySteps?: string[];
    demoNote?: string;
    scope: string;
    stack: string[];
    contactTitle: string;
    contactText: string;
};
