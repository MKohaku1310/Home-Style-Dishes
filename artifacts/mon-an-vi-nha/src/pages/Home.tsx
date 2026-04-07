import { Link } from "wouter";
import { ArrowRight } from "lucide-react";
import DishCard from "@/components/DishCard";
import { dishes } from "@/data/dishes";

const featured = dishes[0];
const sideList = dishes.slice(1, 5);
const randomThree = [...dishes].sort(() => Math.random() - 0.5).slice(0, 3);

export default function Home() {
  return (
    <div className="paper-texture">
      {/* ── FRONT PAGE LAYOUT ── */}
      <div className="page-container" style={{ paddingTop: "2rem" }}>

        {/* Section label */}
        <p className="section-label">Số Mới Nhất</p>

        {/* Two-column newspaper layout */}
        <div
          style={{
            display: "grid",
            gridTemplateColumns: "60% 40%",
            gap: 0,
            borderTop: "3px solid var(--color-ink)",
            borderBottom: "1px solid var(--color-rule)",
          }}
        >
          {/* LEFT — Featured article */}
          <article
            style={{
              borderRight: "2px solid var(--color-gold-light)",
              paddingRight: "1.75rem",
              paddingTop: "1.25rem",
              paddingBottom: "1.5rem",
            }}
          >
            <Link href={`/dish/${featured.slug}`}>
              <div style={{ overflow: "hidden", marginBottom: "1rem" }}>
                <img
                  src={featured.image}
                  alt={featured.name}
                  className="sepia-image"
                  style={{
                    width: "100%",
                    aspectRatio: "16/9",
                    objectFit: "cover",
                    display: "block",
                    cursor: "pointer",
                  }}
                />
              </div>
              <span className="category-badge" style={{ marginBottom: "0.5rem", display: "inline-block" }}>
                Tiêu Điểm
              </span>
              <h2
                style={{
                  fontFamily: "var(--font-display)",
                  fontSize: "clamp(1.5rem, 3vw, 2.1rem)",
                  fontWeight: 900,
                  color: "var(--color-ink)",
                  lineHeight: 1.15,
                  margin: "0.4rem 0 0.75rem",
                  cursor: "pointer",
                }}
              >
                {featured.name}
              </h2>
            </Link>
            <p
              style={{
                fontFamily: "var(--font-body)",
                fontSize: "0.95rem",
                color: "var(--color-ink-light)",
                lineHeight: 1.8,
                marginBottom: "1rem",
              }}
            >
              {featured.description}
            </p>
            <div
              style={{
                display: "flex",
                gap: "1.5rem",
                fontFamily: "var(--font-caption)",
                fontSize: "0.72rem",
                color: "var(--color-muted)",
                borderTop: "1px solid var(--color-rule)",
                paddingTop: "0.65rem",
                marginTop: "0.5rem",
              }}
            >
              <span>📍 {featured.region}</span>
              <span>⏱ {featured.prepTime} sơ chế · {featured.cookTime} nấu</span>
              <span>👥 {featured.servings} người</span>
            </div>
            <div style={{ marginTop: "1rem" }}>
              <Link href={`/dish/${featured.slug}`} className="btn-primary">
                Xem Công Thức <ArrowRight size={13} />
              </Link>
            </div>
          </article>

          {/* RIGHT — Text-only list */}
          <aside style={{ paddingLeft: "1.75rem", paddingTop: "1.25rem" }}>
            <p
              style={{
                fontFamily: "var(--font-caption)",
                fontSize: "0.68rem",
                fontWeight: 700,
                letterSpacing: "0.18em",
                textTransform: "uppercase",
                color: "var(--color-muted)",
                borderBottom: "1px solid var(--color-rule)",
                paddingBottom: "0.4rem",
                marginBottom: "0.5rem",
              }}
            >
              Các Món Khác
            </p>
            <ul style={{ listStyle: "none", display: "flex", flexDirection: "column", gap: 0 }}>
              {sideList.map((dish, i) => (
                <li
                  key={dish.id}
                  style={{
                    borderBottom: i < sideList.length - 1 ? "1px solid var(--color-paper-dark)" : "none",
                    paddingBottom: "0.75rem",
                    marginBottom: "0.75rem",
                  }}
                >
                  <Link href={`/dish/${dish.slug}`}>
                    <span className="category-badge" style={{ marginBottom: "0.3rem", display: "inline-block" }}>
                      {dish.region}
                    </span>
                    <h4
                      style={{
                        fontFamily: "var(--font-display)",
                        fontSize: "1rem",
                        fontWeight: 700,
                        color: "var(--color-ink)",
                        cursor: "pointer",
                        lineHeight: 1.25,
                        marginBottom: "0.3rem",
                        transition: "color 0.2s",
                      }}
                      onMouseEnter={(e) => ((e.target as HTMLElement).style.color = "var(--color-red)")}
                      onMouseLeave={(e) => ((e.target as HTMLElement).style.color = "var(--color-ink)")}
                    >
                      {dish.name}
                    </h4>
                    <p
                      style={{
                        fontFamily: "var(--font-body)",
                        fontSize: "0.78rem",
                        color: "var(--color-muted)",
                        lineHeight: 1.6,
                        display: "-webkit-box",
                        WebkitLineClamp: 2,
                        WebkitBoxOrient: "vertical",
                        overflow: "hidden",
                      }}
                    >
                      {dish.description}
                    </p>
                  </Link>
                </li>
              ))}
            </ul>
            <Link
              href="/menu"
              className="btn-outline"
              style={{ marginTop: "0.5rem", display: "inline-flex", fontSize: "0.72rem" }}
            >
              Xem Toàn Bộ Thực Đơn →
            </Link>
          </aside>
        </div>

        {/* ── ORNAMENT ── */}
        <div className="ornament-divider">❦ Hôm Nay Nấu Gì? ❦</div>

        {/* ── RANDOM 3 CARDS ── */}
        <div
          style={{
            display: "grid",
            gridTemplateColumns: "repeat(auto-fill, minmax(280px, 1fr))",
            gap: "1.25rem",
            marginBottom: "3rem",
          }}
        >
          {randomThree.map((dish) => (
            <DishCard key={dish.id} dish={dish} />
          ))}
        </div>

        {/* ── ORNAMENT ── */}
        <div className="ornament-divider">◆ ◆ ◆</div>

        {/* ── CTA BANNER ── */}
        <div
          style={{
            background: "var(--color-ink)",
            color: "var(--color-paper)",
            border: "1px solid var(--color-gold-light)",
            padding: "2.5rem",
            textAlign: "center",
            marginBottom: "3rem",
          }}
        >
          <p
            style={{
              fontFamily: "var(--font-caption)",
              fontSize: "0.7rem",
              letterSpacing: "0.2em",
              textTransform: "uppercase",
              color: "var(--color-gold-light)",
              marginBottom: "0.5rem",
            }}
          >
            — Ấm Bụng Mỗi Ngày —
          </p>
          <h2
            style={{
              fontFamily: "var(--font-display)",
              fontSize: "clamp(1.4rem, 3vw, 2rem)",
              color: "var(--color-paper)",
              marginBottom: "0.75rem",
            }}
          >
            Bắt Đầu Từ Bếp Nhà
          </h2>
          <p
            style={{
              fontFamily: "var(--font-body)",
              fontSize: "0.9rem",
              color: "var(--color-paper-dark)",
              marginBottom: "1.25rem",
              maxWidth: "480px",
              margin: "0 auto 1.25rem",
            }}
          >
            Hàng chục công thức nấu ăn truyền thống, hướng dẫn từng bước rõ ràng, nguyên liệu dễ tìm.
          </p>
          <Link href="/menu" className="btn-primary">
            Khám Phá Thực Đơn <ArrowRight size={13} />
          </Link>
        </div>
      </div>

      {/* Responsive styles for the two-column grid */}
      <style>{`
        @media (max-width: 768px) {
          .home-two-col {
            grid-template-columns: 1fr !important;
          }
          .home-two-col > aside {
            border-right: none !important;
            border-top: 2px solid var(--color-gold-light) !important;
            padding-left: 0 !important;
            padding-top: 1.25rem !important;
          }
        }
      `}</style>
    </div>
  );
}
