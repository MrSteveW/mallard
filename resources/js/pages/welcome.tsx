// welcome.tsx
import Login from '@/pages/auth/login';

export default function Welcome() {
    return (
        <div>
            {/* your hero/marketing content */}
            <Login canResetPassword={true} canRegister={true} />
        </div>
    );
}
