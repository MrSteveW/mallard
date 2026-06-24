import { Link } from '@inertiajs/react'
import type { InertiaLinkProps } from '@inertiajs/react'
import type { VariantProps } from 'class-variance-authority'
import { buttonVariants } from '@/components/ui/button'
import { cn } from '@/lib/utils'

export function PrimaryLink({
    className,
    variant,
    size,
    children,
    ...props
}: InertiaLinkProps & VariantProps<typeof buttonVariants>) {
    return (
        <Link
            className={cn(buttonVariants({ variant, size, className }))}
            {...props}
        >
            {children}
        </Link>
    )
}