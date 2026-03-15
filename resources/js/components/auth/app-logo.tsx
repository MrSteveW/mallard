import logo from '@/../images/logo.webp';

export default function AppLogo() {
    return (
        <>
            <div className="flex aspect-square size-8 items-center justify-center rounded-md text-mallard-green">
                <img src={logo} alt="logo" width="300" />
            </div>
            <div className="ml-1 grid flex-1 text-left text-sm">
                <span className="mb-0.5 truncate leading-tight font-semibold">
                    Mallard
                </span>
            </div>
        </>
    );
}
