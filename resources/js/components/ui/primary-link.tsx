import { Link } from '@inertiajs/react'
import type { InertiaLinkProps } from '@inertiajs/react'
import type { VariantProps } from 'class-variance-authority'
import { cn } from '@/lib/utils'
import { buttonVariants } from '@/components/ui/button'

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