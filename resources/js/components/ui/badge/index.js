import { cva } from 'class-variance-authority';

export { default as Badge } from "./Badge.vue";

export const badgeVariants = cva(
  'inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-bold uppercase tracking-wider transition-colors focus:outline-none focus:ring-2 focus:ring-bull-blue focus:ring-offset-2',
  {
    variants: {
      variant: {
        default: 'border-transparent bg-bull-blue text-white',
        'neo-blue': 'bg-bull-blue text-bull-neon border-transparent',
        'neo-neon': 'bg-bull-neon text-bull-blue border border-bull-blue',
        'neo-pink': 'bg-bull-pink text-white border border-bull-blue',
        'neo-aqua': 'bg-bull-aqua text-white border border-bull-blue',
        'neo-dark': 'bg-gray-800 text-white border-transparent',
      },
      shape: {
        default: 'rounded-sm',
        pill: 'rounded-full px-3 py-1',
        square: 'rounded-none',
      }
    },
    defaultVariants: {
      variant: 'default',
      shape: 'default',
    },
  },
);