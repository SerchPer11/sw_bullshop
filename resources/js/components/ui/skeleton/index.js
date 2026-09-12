import { cva } from "class-variance-authority";

export { default as Skeleton } from "./Skeleton.vue";

export const skeletonVariants = cva(
    'animate-pulse',
  {
    variants: {
      variant: {
        default: 'bg-bull-blue/10',
        'bordered-blue': 'bg-bull-blue/5 border-2 border-bull-blue/20',
        'heavy-blue': 'bg-bull-blue/20 border-4 border-bull-blue',
        'bordered-pink': 'bg-bull-pink/5 border-2 border-bull-pink/20',
        'heavy-pink': 'bg-bull-pink/20 border-4 border-bull-pink',
        'bordered-aqua': 'bg-bull-aqua/5 border-2 border-bull-aqua/20',
        'heavy-aqua': 'bg-bull-aqua/20 border-4 border-bull-aqua',
        'bordered-neon': 'bg-bull-neon/5 border-2 border-bull-neon/20',
        'heavy-neon': 'bg-bull-neon/20 border-4 border-bull-neon',
      },
      shape: {
        default: 'rounded-none',
        text: 'rounded-none h-4',
        title: 'rounded-none h-8 md:h-10',
        avatar: 'rounded-full',
        button: 'rounded-none h-12',
      }
    },
    defaultVariants: {
      variant: 'default',
      shape: 'default',
    },
  }
)
