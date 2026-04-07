import { Switch, Route, Router as WouterRouter } from "wouter";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import Home from "@/pages/Home";
import Menu from "@/pages/Menu";
import DishDetail from "@/pages/DishDetail";
import About from "@/pages/About";
import Contact from "@/pages/Contact";

function NotFound() {
  return (
    <div
      style={{
        minHeight: "60vh",
        display: "flex",
        flexDirection: "column",
        alignItems: "center",
        justifyContent: "center",
        textAlign: "center",
        padding: "2rem",
      }}
    >
      <p
        style={{
          fontFamily: "var(--font-display)",
          fontSize: "1.3rem",
          fontStyle: "italic",
          color: "var(--color-muted)",
          marginBottom: "1rem",
        }}
      >
        ❦ Trang này không tồn tại ❦
      </p>
      <a
        href="/"
        style={{
          fontFamily: "var(--font-ui)",
          fontSize: "0.8rem",
          color: "var(--color-red)",
          textDecoration: "underline",
        }}
      >
        Về Trang Chủ
      </a>
    </div>
  );
}

function Router() {
  return (
    <div style={{ minHeight: "100vh", display: "flex", flexDirection: "column" }}>
      <Navbar />
      <main style={{ flex: 1 }}>
        <Switch>
          <Route path="/" component={Home} />
          <Route path="/menu" component={Menu} />
          <Route path="/dish/:slug" component={DishDetail} />
          <Route path="/about" component={About} />
          <Route path="/contact" component={Contact} />
          <Route component={NotFound} />
        </Switch>
      </main>
      <Footer />
    </div>
  );
}

function App() {
  return (
    <WouterRouter base={import.meta.env.BASE_URL.replace(/\/$/, "")}>
      <Router />
    </WouterRouter>
  );
}

export default App;
