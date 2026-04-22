import { AppContent } from '@/components/auth/app-content';
import { AppFooter } from '@/components/auth/app-footer';
import { AppHeader } from '@/components/auth/app-header';
import { AppShell } from '@/components/auth/app-shell';
import type { AppLayoutProps } from '@/types/ui';

export default function AppHeaderLayout({ children }: AppLayoutProps) {
    return (
        <AppShell>
            <AppHeader />
            <AppContent>{children}</AppContent>
            <AppFooter />
        </AppShell>
    );
}
