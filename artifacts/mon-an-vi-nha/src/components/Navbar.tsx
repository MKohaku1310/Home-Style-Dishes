import { useState, useEffect } from "react";
import { Link, useLocation } from "wouter";
import { Sun, Moon, Menu, X } from "lucide-react";
import { useTheme } from "@/hooks/useTheme";

const navLinks = [
  { href: "/", label: "Trang Chủ" },
  { href: "/menu", label: "Thực Đơn" },
  { href: "/about", label: "Giới Thiệu" },
  { href: "/contact", label: "Liên Hệ" },
];

export default function Navbar() {
  const [location] = useLocation();
  const { isDark, toggleDark } = useTheme();
  const [scrolled, setScrolled] = useState(false);
  const [mobileOpen, setMobileOpen] = useState(false);

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 10);
    window.addEventListener("scroll", onScroll);
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  const today = new Date().toLocaleDateString("vi-VN", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  });

  return (
    <header className={`site-header paper-texture${scrolled ? " scrolled" : ""}`}>
      {/* Masthead */}
      <div className="masthead-wrapper">
        <div className="page-container" style={{ position: "relative" }}>
          {/* Dark toggle — top right */}
          <button
            onClick={toggleDark}
            className="dark-toggle"
            aria-label="Chuyển chế độ"
            style={{ position: "absolute", top: "0.5rem", right: 0 }}
          >
            {isDark ? <Sun size={14} /> : <Moon size={14} />}
          </button>

          {/* Mobile hamburger — top left */}
          <button
            className="hamburger"
            onClick={() => setMobileOpen((v) => !v)}
            aria-label="Menu"
            style={{ position: "absolute", top: "0.5rem", left: 0 }}
          >
            {mobileOpen ? <X size={20} color="var(--color-ink)" /> : <Menu size={20} color="var(--color-ink)" />}
          </button>

          <p className="masthead-dateline">{today} · Ẩm Thực Gia Đình Việt Nam</p>

          <Link href="/">
            <h1 className="masthead-title" style={{ cursor: "pointer" }}>
              Món Ăn Vị Nhà
            </h1>
          </Link>

          <p className="masthead-tagline">
            "Hương vị bếp nhà — Ký ức một thời"
          </p>
        </div>

        {/* Triple rule */}
        <div className="page-container">
          <hr className="masthead-rule" />
        </div>
      </div>

      {/* Desktop Navigation */}
      <nav className="nav-bar" aria-label="Điều hướng chính">
        {navLinks.map((link) => (
          <Link
            key={link.href}
            href={link.href}
            className={`nav-link${location === link.href ? " active" : ""}`}
          >
            {link.label}
          </Link>
        ))}
      </nav>

      {/* Mobile Menu */}
      <div className={`mobile-menu${mobileOpen ? " open" : ""}`}>
        {navLinks.map((link) => (
          <Link
            key={link.href}
            href={link.href}
            onClick={() => setMobileOpen(false)}
            className={`mobile-nav-link${location === link.href ? " active" : ""}`}
          >
            {link.label}
          </Link>
        ))}
      </div>
    </header>
  );
}
