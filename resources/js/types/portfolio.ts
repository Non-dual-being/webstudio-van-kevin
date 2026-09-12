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
    scope: string;
    stack: string[];
    contactTitle: string;
    contactText: string;
};
