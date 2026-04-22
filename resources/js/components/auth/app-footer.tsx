export function AppFooter() {
    return (
        <>
            <div className="h-11" />
            <footer className="fixed bottom-0 z-40 w-full border-t border-sidebar-border/80 bg-background py-3">
                <div className="mx-auto flex max-w-7xl items-center justify-between px-4 text-xs text-muted-foreground">
                    <span>Mallard v1.0</span>
                    <span>© 2026 Steve Williams</span>
                    <a
                        href="https://github.com/MrSteveW/mallard"
                        target="_blank"
                        rel="noopener noreferrer"
                        className="transition-colors hover:text-foreground"
                    >
                        github.com/MrSteveW
                    </a>
                </div>
            </footer>
        </>
    );
}
