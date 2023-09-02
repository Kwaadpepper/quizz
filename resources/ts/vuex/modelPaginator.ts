export type Paginator<T> = {
    data: Array<T>;
    links: typeof ApiPaginatorLinks;
    meta: typeof PaginatorMeta;
    name: string;
};

export const createPaginator: <T>() => Paginator<T>  = () => {
    return {
        data: [],
        links: ApiPaginatorLinks,
        meta: PaginatorMeta,
        name: ""
    };
};

const ApiPaginatorLinks: {
    first: string;
    last: string;
    prev: string|null;
    next: string|null;
} = {
    first: "",
    last: "",
    prev: null,
    next: null
};

const PaginatorMeta : {
    /** The actual page where are on */
    current_page: number;
    /** The last page for this paginator */
    last_page: number;
    /** The range start for this page */
    from: number|null;
    /** The range end for this page */
    to: number|null;
    /** The total amount of items */
    total: number;
    /** The amount of items per page */
    per_page: number;
    /** The actual resource path */
    path: string;
    links: Array<BladePaginatorLink>
} = {
    current_page: 1,
    last_page: 1,
    from: null,
    to: null,
    total: 0,
    per_page: 15,
    path: "",
    links: []
};

type BladePaginatorLink = {
    /** The link url */
    url: string|null;
    /** The link label */
    label: string;
    /** The link status, wether it is the active page or not */
    active: boolean;
};
