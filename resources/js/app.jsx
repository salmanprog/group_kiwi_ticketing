import './bootstrap';
import { createRoot } from 'react-dom/client';
import AppRoute from "./Route/Index";

function App() {
    return (
        <>
            <AppRoute />
        </>
    );
}
// Render your React component instead
const root = createRoot(document.getElementById('app'));
root.render(<App />);
