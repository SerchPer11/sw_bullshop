import { reactive, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { useLoading } from './useLoading';

export const useFilters = (filtersOn, routeName) => {
    const { isLoading, setLoading } = useLoading();
    const filters = reactive({ ...filtersOn });
    let searchTimeoutId;

    const applyFilters = (preserveState = true) => {
        setLoading(true);
        router.get(route(routeName), filters, {
            preserveScroll: true,
            preserveState,
            replace: true,
            onFinish: () => setLoading(false),
        });
    };

    const clearFilters = () => {
        Object.keys(filters).forEach((key) => {
            if (key === 'search') {
                filters[key] = '';
            }

            if (key === 'page') {
                filters[key] = 1;
            }
        });
        applyFilters(true);
    };

    const sortByColumn = (order, direction) => {
        filters.order = order;
        filters.direction = direction;
        applyFilters(true);
    };

    watch(
        () => filters.search,
        () => {
            clearTimeout(searchTimeoutId);
            filters.page = 1;
            searchTimeoutId = setTimeout(() => applyFilters(true), 500);
        },
    );

    return {
        filters,
        applyFilters,
        clearFilters,
        sortByColumn,
        isLoading,
        setLoading,
    };
};
