import { Link } from "wouter";

export default function Footer() {
  return (
    <footer className="site-footer paper-texture">
      <div className="page-container">
        <div style={{ display: "grid", gridTemplateColumns: "repeat(auto-fit, minmax(200px, 1fr))", gap: "2rem" }}>
          {/* Brand */}
          <div>
            <h3 style={{ fontFamily: "var(--font-display)", fontSize: "1.4rem", color: "var(--color-gold-light)", marginBottom: "0.75rem" }}>
              Món Ăn Vị Nhà
            </h3>
            <p style={{ color: "var(--color-paper-dark)", fontSize: "0.82rem", lineHeight: 1.8 }}>
              Lưu giữ và chia sẻ những công thức nấu ăn truyền thống của ẩm thực gia đình Việt Nam. Bếp nhà — nơi tình thương bắt đầu.
            </p>
          </div>

          {/* Links */}
          <div>
            <h3 style={{ marginBottom: "0.75rem", fontSize: "0.75rem", letterSpacing: "0.12em", textTransform: "uppercase" }}>
              Khám Phá
            </h3>
            <ul style={{ listStyle: "none", display: "flex", flexDirection: "column", gap: "0.4rem" }}>
              {[
                { href: "/", label: "Trang Chủ" },
                { href: "/menu", label: "Thực Đơn" },
                { href: "/about", label: "Giới Thiệu" },
                { href: "/contact", label: "Liên Hệ" },
              ].map((l) => (
                <li key={l.href}>
                  <Link href={l.href}>{l.label}</Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Categories */}
          <div>
            <h3 style={{ marginBottom: "0.75rem", fontSize: "0.75rem", letterSpacing: "0.12em", textTransform: "uppercase" }}>
              Danh Mục
            </h3>
            <ul style={{ listStyle: "none", display: "flex", flexDirection: "column", gap: "0.4rem" }}>
              {["Canh & Súp", "Kho & Rim", "Xào & Chiên", "Cơm", "Bún & Phở"].map((c) => (
                <li key={c}>
                  <span style={{ color: "var(--color-paper-dark)", fontSize: "0.82rem" }}>{c}</span>
                </li>
              ))}
            </ul>
          </div>
        </div>

        <hr className="footer-divider" />

        <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", flexWrap: "wrap", gap: "0.5rem" }}>
          <p style={{ fontSize: "0.75rem", color: "rgba(236,219,184,0.5)" }}>
            © 2025 Món Ăn Vị Nhà · Mọi quyền được bảo lưu
          </p>
          <p style={{ fontSize: "0.75rem", color: "rgba(236,219,184,0.5)", fontStyle: "italic" }}>
            Làm bằng tình yêu với bếp nhà Việt Nam ❦
          </p>
        </div>
      </div>
    </footer>
  );
}
