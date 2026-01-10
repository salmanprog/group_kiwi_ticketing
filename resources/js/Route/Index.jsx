import React, { lazy, memo, Suspense } from "react";
import { BrowserRouter, Routes, Route } from "react-router-dom";

//lazy load component
const HomePage = lazy(() => import("../Components/Home/Index"));

const Index = () => {
    return(
        <React.Fragment>
            <BrowserRouter>
                <Routes>
                    <Route
                        exact
                        path="/"
                        name="home"
                        element={<HomePage />}
                    />
                </Routes>
            </BrowserRouter>
        </React.Fragment>
    );
}
export default Index;
