export interface Resource<T> {
    data: T;
}

export interface Paginated<T> {
    data: T[];
    links: {
        first: string;
        last: string;
        prev: string | null;
        next: string | null;
    };
    meta: {
        current_page: number;
        from: number;
        last_page: number;
        path: string;
        per_page: number;
        to: number;
        total: number; 
    };
}



export interface User {
    id: number;
    name: string;
    email: string;
}

export interface RecipeIngredient {
    id: number;
    ingredient: string;
    amount: number;
    unit?: string;
    descriptor?: string;
}

export interface RecipeStep {
    id: number;
    stepNumber: number;
    description: string;
}

export interface Recipe {
    id: number;
    name: string;
    slug: string;
    description: string;
    ingredients: RecipeIngredient[];
    steps: RecipeStep[];
    author: User;
}
