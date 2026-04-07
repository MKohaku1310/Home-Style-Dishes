import { useRoute, useLocation } from "wouter";
import { ArrowLeft, Clock, Users, MapPin, ChefHat } from "lucide-react";
import { dishes, categoryLabels } from "@/data/dishes";

export default function DishDetail() {
  const [, params] = useRoute("/dish/:slug");
  const [, navigate] = useLocation();
  const slug = params?.slug;
  const dish = dishes.find((d) => d.slug === slug);

  if (!dish) {
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
          ❦ Không tìm thấy món ăn này ❦
        </p>
        <button className="btn-primary" onClick={() => navigate("/menu")}>
          <ArrowLeft size={13} /> Quay Lại Thực Đơn
        </button>
      </div>
    );
  }

  return (
    <div className="paper-texture" style={{ minHeight: "80vh" }}>
      {/* Hero image full-width */}
      <div style={{ position: "relative", maxHeight: "420px", overflow: "hidden" }}>
        <img
          src={dish.image}
          alt={dish.name}
          style={{
            width: "100%",
            height: "420px",
            objectFit: "cover",
            display: "block",
            filter: "sepia(35%) contrast(1.05) brightness(0.92)",
          }}
        />
        <div
          style={{
            position: "absolute",
            inset: 0,
            background:
              "linear-gradient(to bottom, transparent 30%, rgba(26,18,8,0.65) 100%)",
          }}
        />
        <div
          style={{
            position: "absolute",
            bottom: "1.5rem",
            left: "1.25rem",
            right: "1.25rem",
          }}
        >
          <div className="page-container">
            <button
              onClick={() => navigate("/menu")}
              style={{
                fontFamily: "var(--font-caption)",
                fontSize: "0.72rem",
                color: "rgba(245,234,208,0.85)",
                display: "flex",
                alignItems: "center",
                gap: "0.3rem",
                background: "none",
                border: "none",
                cursor: "pointer",
                marginBottom: "0.6rem",
                letterSpacing: "0.06em",
                textTransform: "uppercase",
              }}
            >
              <ArrowLeft size={12} /> Thực Đơn
            </button>
            <span className="category-badge" style={{ marginBottom: "0.4rem", display: "inline-block", color: "var(--color-gold-light)", borderColor: "var(--color-gold-light)" }}>
              {categoryLabels[dish.category]}
            </span>
            <h1
              style={{
                fontFamily: "var(--font-display)",
                fontSize: "clamp(1.8rem, 5vw, 3rem)",
                color: "var(--color-paper)",
                fontWeight: 900,
                lineHeight: 1.1,
              }}
            >
              {dish.name}
            </h1>
          </div>
        </div>
      </div>

      {/* Content */}
      <div className="page-container" style={{ paddingTop: "2rem", paddingBottom: "3rem" }}>
        {/* Meta bar */}
        <div
          style={{
            display: "flex",
            flexWrap: "wrap",
            gap: "1.5rem",
            padding: "1rem 0",
            borderTop: "3px solid var(--color-ink)",
            borderBottom: "1px solid var(--color-rule)",
            marginBottom: "2rem",
            fontFamily: "var(--font-caption)",
            fontSize: "0.78rem",
            color: "var(--color-muted)",
          }}
        >
          <span style={{ display: "flex", alignItems: "center", gap: "0.4rem" }}>
            <MapPin size={13} color="var(--color-red)" /> {dish.region}
          </span>
          <span style={{ display: "flex", alignItems: "center", gap: "0.4rem" }}>
            <Clock size={13} color="var(--color-red)" /> Sơ chế: {dish.prepTime}
          </span>
          <span style={{ display: "flex", alignItems: "center", gap: "0.4rem" }}>
            <Clock size={13} color="var(--color-gold)" /> Nấu: {dish.cookTime}
          </span>
          <span style={{ display: "flex", alignItems: "center", gap: "0.4rem" }}>
            <Users size={13} color="var(--color-red)" /> {dish.servings} người
          </span>
          <span style={{ display: "flex", alignItems: "center", gap: "0.4rem" }}>
            <ChefHat size={13} color="var(--color-red)" />
            <span
              style={{
                color:
                  dish.difficulty === "Dễ"
                    ? "var(--color-green)"
                    : dish.difficulty === "Trung bình"
                    ? "var(--color-gold)"
                    : "var(--color-red)",
                fontWeight: 600,
              }}
            >
              {dish.difficulty}
            </span>
          </span>
        </div>

        {/* Description */}
        <p
          style={{
            fontFamily: "var(--font-body)",
            fontSize: "1.05rem",
            lineHeight: 1.85,
            color: "var(--color-ink-light)",
            marginBottom: "2.5rem",
            maxWidth: "760px",
          }}
        >
          {dish.description}
        </p>

        {/* Two-column: Ingredients | Steps */}
        <div
          style={{
            display: "grid",
            gridTemplateColumns: "1fr 1.7fr",
            gap: "2.5rem",
            marginBottom: "2.5rem",
          }}
          className="detail-grid"
        >
          {/* Ingredients */}
          <div>
            <h2
              style={{
                fontFamily: "var(--font-display)",
                fontSize: "1.1rem",
                color: "var(--color-ink)",
                borderBottom: "2px solid var(--color-ink)",
                paddingBottom: "0.4rem",
                marginBottom: "1rem",
                letterSpacing: "0.02em",
              }}
            >
              Nguyên Liệu
            </h2>
            <ul style={{ listStyle: "none" }}>
              {dish.ingredients.map((ing, i) => (
                <li
                  key={i}
                  style={{
                    display: "flex",
                    gap: "0.75rem",
                    alignItems: "baseline",
                    borderBottom: "1px dotted var(--color-rule)",
                    padding: "0.45rem 0",
                    fontFamily: "var(--font-body)",
                    fontSize: "0.88rem",
                  }}
                >
                  <span
                    style={{
                      fontFamily: "var(--font-caption)",
                      fontWeight: 600,
                      color: "var(--color-red)",
                      minWidth: "70px",
                      fontSize: "0.82rem",
                    }}
                  >
                    {ing.amount}
                  </span>
                  <span style={{ color: "var(--color-ink-light)" }}>{ing.name}</span>
                </li>
              ))}
            </ul>
          </div>

          {/* Steps */}
          <div>
            <h2
              style={{
                fontFamily: "var(--font-display)",
                fontSize: "1.1rem",
                color: "var(--color-ink)",
                borderBottom: "2px solid var(--color-ink)",
                paddingBottom: "0.4rem",
                marginBottom: "1rem",
              }}
            >
              Cách Nấu
            </h2>
            {dish.steps.map((s) => (
              <div key={s.step} className="recipe-step">
                <span className="recipe-step__num">{s.step}.</span>
                <div>
                  <p className="recipe-step__title">Bước {s.step}: {s.title}</p>
                  <p className="recipe-step__text">{s.instruction}</p>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Story */}
        <div className="ornament-divider">❦ Câu Chuyện Món Ăn ❦</div>
        <blockquote
          style={{
            fontFamily: "var(--font-body)",
            fontStyle: "italic",
            fontSize: "1rem",
            lineHeight: 1.9,
            color: "var(--color-ink-light)",
            borderLeft: "3px solid var(--color-gold-light)",
            paddingLeft: "1.25rem",
            maxWidth: "680px",
            margin: "0 auto 3rem",
          }}
        >
          {dish.story}
        </blockquote>

        {/* Back */}
        <div style={{ borderTop: "1px solid var(--color-rule)", paddingTop: "1.5rem" }}>
          <button className="btn-outline" onClick={() => navigate("/menu")}>
            <ArrowLeft size={13} /> Quay Lại Thực Đơn
          </button>
        </div>
      </div>

      <style>{`
        @media (max-width: 768px) {
          .detail-grid {
            grid-template-columns: 1fr !important;
          }
        }
      `}</style>
    </div>
  );
}
