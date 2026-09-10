import { cva } from "class-variance-authority";

export { default as Button } from "./Button.vue";
//export { buttonVariants };
export const buttonVariants = cva(
  'inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-bold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-bull-blue disabled:pointer-events-none disabled:opacity-50 uppercase tracking-wide',
  {
    variants: {
      variant: {
        'neo-neon': 'bg-bull-neon text-bull-blue border-2 border-bull-blue shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] hover:shadow-[2px_2px_0px_0px_rgba(0,39,49,1)] hover:translate-x-[2px] hover:translate-y-[2px] active:translate-x-[4px] active:translate-y-[4px] active:shadow-none',
        'neo-pink': 'bg-bull-pink text-white border-2 border-bull-blue shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] hover:shadow-[2px_2px_0px_0px_rgba(0,39,49,1)] hover:translate-x-[2px] hover:translate-y-[2px] active:translate-x-[4px] active:translate-y-[4px] active:shadow-none',
        'neo-white': 'bg-white text-bull-blue border-2 border-bull-blue shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] hover:shadow-[2px_2px_0px_0px_rgba(0,39,49,1)] hover:translate-x-[2px] hover:translate-y-[2px] active:translate-x-[4px] active:translate-y-[4px] active:shadow-none',
        'neo-blue': 'bg-bull-blue text-white border-2 border-bull-blue shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] hover:shadow-[2px_2px_0px_0px_rgba(0,39,49,1)] hover:translate-x-[2px] hover:translate-y-[2px] active:translate-x-[4px] active:translate-y-[4px] active:shadow-none',
        'neo-aqua': 'bg-bull-aqua text-white border-2 border-bull-blue shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] hover:shadow-[2px_2px_0px_0px_rgba(0,39,49,1)] hover:translate-x-[2px] hover:translate-y-[2px] active:translate-x-[4px] active:translate-y-[4px] active:shadow-none',
        'neo-blue-outline': 'bg-transparent text-bull-blue border-2 border-bull-blue shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] hover:shadow-[2px_2px_0px_0px_rgba(0,39,49,1)] hover:translate-x-[2px] hover:translate-y-[2px] active:translate-x-[4px] active:translate-y-[4px] active:shadow-none',
        'neo-aqua-outline': 'bg-transparent text-bull-aqua border-2 border-bull-aqua shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] hover:shadow-[2px_2px_0px_0px_rgba(0,39,49,1)] hover:translate-x-[2px] hover:translate-y-[2px] active:translate-x-[4px] active:translate-y-[4px] active:shadow-none',
        'neo-pink-outline': 'bg-transparent text-bull-pink border-2 border-bull-pink shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] hover:shadow-[2px_2px_0px_0px_rgba(0,39,49,1)] hover:translate-x-[2px] hover:translate-y-[2px] active:translate-x-[4px] active:translate-y-[4px] active:shadow-none',
        'neo-neon-outline': 'bg-transparent text-bull-neon border-2 border-bull-neon shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] hover:shadow-[2px_2px_0px_0px_rgba(0,39,49,1)] hover:translate-x-[2px] hover:translate-y-[2px] active:translate-x-[4px] active:translate-y-[4px] active:shadow-none',
        'neo-blue-ghost': 'bg-transparent text-bull-blue border-2 border-bull-blue shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] hover:bg-bull-blue/10 hover:shadow-[2px_2px_0px_0px_rgba(0,39,49,1)] hover:translate-x-[2px] hover:translate-y-[2px] active:translate-x-[4px] active:translate-y-[4px] active:shadow-none',
        'neo-aqua-ghost': 'bg-transparent text-bull-aqua border-2 border-bull-aqua shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] hover:bg-bull-aqua/10 hover:shadow-[2px_2px_0px_0px_rgba(0,39,49,1)] hover:translate-x-[2px] hover:translate-y-[2px] active:translate-x-[4px] active:translate-y-[4px] active:shadow-none',
        'neo-pink-ghost': 'bg-transparent text-bull-pink border-2 border-bull-pink shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] hover:bg-bull-pink/10 hover:shadow-[2px_2px_0px_0px_rgba(0,39,49,1)] hover:translate-x-[2px] hover:translate-y-[2px] active:translate-x-[4px] active:translate-y-[4px] active:shadow-none',
        'neo-neon-ghost': 'bg-transparent text-bull-neon border-2 border-bull-neon shadow-[4px_4px_0px_0px_rgba(0,39,49,1)] hover:bg-bull-neon/10 hover:shadow-[2px_2px_0px_0px_rgba(0,39,49,1)] hover:translate-x-[2px] hover:translate-y-[2px] active:translate-x-[4px] active:translate-y-[4px] active:shadow-none',
        default: 'bg-bull-blue text-white hover:bg-bull-blue/90',
        outline: 'border-2 border-bull-blue bg-transparent hover:bg-bull-blue hover:text-white',
        ghost: 'hover:bg-bull-blue/10 text-bull-blue',
        link: 'text-bull-pink underline-offset-4 hover:underline',
      },
      size: {
        default: 'h-10 px-4 text-sm', 
        sm: 'h-9 px-3 text-xs', 
        lg: 'h-12 px-8 text-base', 
        xl: 'h-14 px-12 text-lg', 
        icon: 'h-10 w-10',
      },
      // 3. FORMAS
      shape: {
        square: 'rounded-none',
        rounded: 'rounded-md',
        pill: 'rounded-full',
      }
    },
    defaultVariants: {
      variant: 'default',
      size: 'default',
      shape: 'square',
    },
  },
);
