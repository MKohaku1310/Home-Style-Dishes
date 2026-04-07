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
    <div className="min-h-screen flex flex-col items-center justify-center pt-20 px-4 text-center">
      <div className="text-6xl mb-4">🍽️</div>
      <h1 className="text-3xl font-bold text-foreground mb-2">404 - Không Tìm Thấy</h1>
      <p className="text-muted-foreground mb-6">Trang này không tồn tại.</p>
      <a
        href="/"
        className="bg-primary text-primary-foreground px-5 py-2.5 rounded-xl font-medium hover:bg-primary/90 transition-colors"
      >
        Về Trang Chủ
      </a>
    </div>
  );
}

function Router() {
  return (
    <div className="min-h-screen bg-background flex flex-col">
      <Navbar />
      <main className="flex-1">
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
