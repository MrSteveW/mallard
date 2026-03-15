import { Link } from '@inertiajs/react'
import type { InertiaLinkProps } from '@inertiajs/react'
import { cn } from '@/lib/utils'

export function PrimaryLink({ className, children, ...props }: InertiaLinkProps) {
    return (
        <Link
            className={cn(
                'rounded-md bg-mallard-green px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary/80 focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-black',
                className
            )}
            {...props}
        >
            {children}
        </Link>
    )
}