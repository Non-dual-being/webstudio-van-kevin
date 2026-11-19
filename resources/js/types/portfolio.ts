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
    features: ProjectText[];
    steps: ProjectText[];
    images: {
        src: string;
        mobileSrc: string;
        width: number;
        height: number;
        alt: string;
        caption: string;
    }[];
    trySteps: string[];
    demoNote: string;
    scope: string;
    stack: string[];
    contactTitle: string;
    contactText: string;
};
