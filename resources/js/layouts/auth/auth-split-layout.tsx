import { Link } from '@inertiajs/react';
import logo from '@/../images/logo.webp';
import logolarge from '@/../images/logolarge.webp';
import { AppFooter } from '@/components/auth/app-footer';
import { home } from '@/routes';
import type { AuthLayoutProps } from '@/types/index';

export default function AuthSplitLayout({
    children,
    description,
}: AuthLayoutProps) {
    return (
        <div className="flex h-dvh flex-col">
            <div className="relative grid flex-1 flex-col items-center justify-center overflow-auto px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
                {/* Desktop and mobile full*/}
                <div className="relative hidden h-full flex-col bg-neutral-100 p-10 text-black lg:flex dark:border-r">
                    <div className="flex flex-col">
                        <div className="p-5 text-center text-8xl text-mallard-green">
                            Mallard
                        </div>
                        <div className="flex w-full items-center justify-center p-5">
                            <img src={logolarge} alt="logo" width="300" />
                        </div>
                        <div className="p-5 text-center text-5xl text-mallard-orange">
                            Get your ducks in order with the complete staff
                            management app
                        </div>
                    </div>
                </div>

                {/* Mobile view only */}
                <div className="w-full lg:p-8">
                    <div className="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-87">
                        <Link
                            href={home()}
                            className="relative z-20 flex items-center justify-center lg:hidden"
                        >
                            <div className="flex flex-col items-center font-zain">
                                <img src={logo} alt="logo" width="70" />
                                <div className="p-5 text-center text-5xl text-mallard-green">
                                    Mallard
                                </div>
                            </div>
                        </Link>
                        <div className="flex flex-col items-center gap-2 text-left sm:items-center sm:text-center">
                            <p className="text-sm text-balance text-muted-foreground">
                                {description}
                            </p>
                        </div>
                        {children}
                    </div>
                </div>
            </div>
            <AppFooter />
        </div>
    );
}
