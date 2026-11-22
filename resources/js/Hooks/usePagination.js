import { Link } from '@inertiajs/vue3';
import { useLoading } from './useLoading';

export const usePagination = (props) => {
    const { isLoading, setLoading } = useLoading();

    const isPrevious = (label) => /Anterior|Previous|&laquo;/.test(label);
    const isNext = (label) => /Siguiente|Next|&raquo;/.test(label);
    const isEllipsis = (label) => /...|&hellip;/.test(label);

    const getComponent = (url) => url ? Link : 'span';

    const getLinkProps = (url) => url ? {
        href: url,
        preserveScroll: props.preserveScroll,
        preserveState: props.preserveState,
        onBefore: () => setLoading(true),
        onFinish: () => setLoading(false),
    } : {};

    const getAriaLabel = (label, active) => {
        if (isPrevious(label)) return 'Página anterior';
        if (isNext(label)) return 'Página siguiente';
        if (active) return `Página actual ${label}`;
        if (!isEllipsis(label)) return `Ir a página ${label}`;
        return undefined;
    };

    const getLinkClasses = (url, label, active) => {
        const base = 'inline-flex h-9 min-w-9 items-center justify-center border-2 border-bull-blue px-2.5 text-xs font-black uppercase tracking-wide transition-all';
        const shadow = 'shadow-[2px_2px_0px_0px_rgba(0,39,49,1)] hover:translate-x-px hover:translate-y-px hover:shadow-none active:translate-x-[2px] active:translate-y-[2px] active:shadow-none';

        if (!url) return `${base} cursor-not-allowed border-bull-blue/15 bg-transparent text-bull-blue/30 shadow-none`;
        if (active) return `${base} ${shadow} bg-bull-neon text-bull-blue`;
        if (isPrevious(label) || isNext(label)) {
            return `${base} ${shadow} bg-white text-bull-blue hover:bg-bull-pink hover:text-white`;
        }

        return `${base} ${shadow} bg-white text-bull-blue hover:bg-bull-neon`;
    };

    return {
        isLoading,
        getComponent,
        getLinkProps,
        getAriaLabel,
        getLinkClasses,
        isPrevious,
        isNext,
    };
};
