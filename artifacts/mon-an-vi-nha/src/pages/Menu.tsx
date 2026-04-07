import { useState, useEffect, useRef } from "react";
import { Search, X } from "lucide-react";
import DishCard from "@/components/DishCard";
import { dishes, categories } from "@/data/dishes";

export default function Menu() {
  const [query, setQuery] = useState("");
  const [debouncedQuery, setDebouncedQuery] = useState("");
  const [activeCategory, setActiveCategory] = useState("all");
  const timerRef = useRef<ReturnType<typeof setTimeout>>();

  useEffect(() => {
    timerRef.current = setTimeout(() => {
      setDebouncedQuery(query);
    }, 300);
    return () => clearTimeout(timerRef.current);
  }, [query]);

  const filtered = dishes.filter((d) => {
    const matchCat = activeCategory === "all" || d.category === activeCategory;
    const q = debouncedQuery.toLowerCase();
    const matchSearch =
      !q ||
      d.name.toLowerCase().includes(q) ||
      d.description.toLowerCase().includes(q) ||
      d.region.toLowerCase().includes(q) ||
      d.ingredients.some((i) => i.name.toLowerCase().includes(q));
    return matchCat && matchSearch;
  });

  return (
    <div className="paper-texture" style={{ minHeight: "80vh" }}>
      {/* Page header */}
      <div className="page-hero">
        <div className="page-container">
          <p className="section-label">Khám Phá</p>
          <h1
            style={{
              fontFamily: "var(--font-display)",
              fontSize: "clamp(1.8rem, 4vw, 2.8rem)",
              color: "var(--color-ink)",
              marginBottom: "0.35rem",
            }}
          >
            Thực Đơn Gia Đình
          </h1>
          <p
            style={{
              fontFamily: "var(--font-body)",
              color: "var(--color-muted)",
              fontSize: "0.9rem",
            }}
          >
            Công thức truyền thống từ bếp nhà Việt Nam — Nam Bộ, Huế, Sài Gòn và hơn thế nữa.
          </p>
        </div>
      </div>

      <div className="page-container" style={{ paddingTop: "1.5rem", paddingBottom: "3rem" }}>
        {/* Search + Filter */}
        <div
          style={{
            display: "flex",
            flexWrap: "wrap",
            gap: "0.75rem",
            alignItems: "flex-start",
            marginBottom: "1.25rem",
          }}
        >
          {/* Search bar */}
          <div className="search-wrap" style={{ flex: "1 1 220px" }}>
            <Search />
            <input
              type="search"
              className="search-input"
              placeholder="Tìm món ăn, nguyên liệu, vùng miền..."
              value={query}
              onChange={(e) => setQuery(e.target.value)}
            />
          </div>

          {/* Clear */}
          {(query || activeCategory !== "all") && (
            <button
              className="btn-ghost"
              onClick={() => {
                setQuery("");
                setActiveCategory("all");
              }}
              style={{ display: "flex", alignItems: "center", gap: "0.3rem" }}
            >
              <X size={12} /> Xóa lọc
            </button>
          )}
        </div>

        {/* Category filter */}
        <div
          style={{
            display: "flex",
            flexWrap: "wrap",
            gap: "0.5rem",
            marginBottom: "1.25rem",
            paddingBottom: "1rem",
            borderBottom: "1px solid var(--color-rule)",
          }}
        >
          {categories.map((cat) => (
            <button
              key={cat.value}
              className={`btn-ghost${activeCategory === cat.value ? " active" : ""}`}
              onClick={() => setActiveCategory(cat.value)}
            >
              {cat.label}
            </button>
          ))}
        </div>

        {/* Result count */}
        <p
          style={{
            fontFamily: "var(--font-caption)",
            fontSize: "0.75rem",
            color: "var(--color-muted)",
            letterSpacing: "0.06em",
            marginBottom: "1.25rem",
          }}
        >
          Tìm thấy <strong style={{ color: "var(--color-ink)" }}>{filtered.length}</strong> món ăn
          {debouncedQuery && ` · cho "${debouncedQuery}"`}
        </p>

        {/* Grid */}
        {filtered.length > 0 ? (
          <div
            style={{
              display: "grid",
              gridTemplateColumns: "repeat(auto-fill, minmax(280px, 1fr))",
              gap: "1.25rem",
            }}
          >
            {filtered.map((dish) => (
              <DishCard key={dish.id} dish={dish} />
            ))}
          </div>
        ) : (
          <div
            style={{
              textAlign: "center",
              padding: "4rem 1rem",
              border: "1px dashed var(--color-rule)",
            }}
          >
            <p
              style={{
                fontFamily: "var(--font-display)",
                fontSize: "1.1rem",
                color: "var(--color-muted)",
                fontStyle: "italic",
              }}
            >
              ❦ Không tìm thấy món phù hợp ❦
            </p>
            <p
              style={{
                fontFamily: "var(--font-caption)",
                fontSize: "0.8rem",
                color: "var(--color-muted)",
                marginTop: "0.5rem",
              }}
            >
              Thử từ khóa khác hoặc xóa bộ lọc
            </p>
          </div>
        )}
      </div>
    </div>
  );
}
