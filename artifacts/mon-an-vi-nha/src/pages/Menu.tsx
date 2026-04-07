import { useState, useEffect } from "react";
import { useLocation } from "wouter";
import { Search, SlidersHorizontal, X } from "lucide-react";
import DishCard from "@/components/DishCard";
import { dishes, categories } from "@/data/dishes";

export default function Menu() {
  const [location] = useLocation();
  const [searchQuery, setSearchQuery] = useState("");
  const [selectedCategory, setSelectedCategory] = useState("Tất cả");
  const [selectedDifficulty, setSelectedDifficulty] = useState("Tất cả");
  const [showFilters, setShowFilters] = useState(false);

  // Read category from URL
  useEffect(() => {
    const params = new URLSearchParams(window.location.search);
    const cat = params.get("category");
    if (cat && categories.includes(cat)) {
      setSelectedCategory(cat);
    }
  }, [location]);

  const difficulties = ["Tất cả", "Dễ", "Trung bình", "Khó"];

  const filtered = dishes.filter((dish) => {
    const matchSearch =
      dish.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
      dish.description.toLowerCase().includes(searchQuery.toLowerCase()) ||
      dish.tags.some((t) => t.toLowerCase().includes(searchQuery.toLowerCase()));
    const matchCategory =
      selectedCategory === "Tất cả" || dish.category === selectedCategory;
    const matchDifficulty =
      selectedDifficulty === "Tất cả" || dish.difficulty === selectedDifficulty;
    return matchSearch && matchCategory && matchDifficulty;
  });

  const clearFilters = () => {
    setSearchQuery("");
    setSelectedCategory("Tất cả");
    setSelectedDifficulty("Tất cả");
  };

  const hasActiveFilters =
    searchQuery || selectedCategory !== "Tất cả" || selectedDifficulty !== "Tất cả";

  return (
    <div className="min-h-screen pt-20 pb-16">
      {/* Page Header */}
      <div className="bg-primary/5 border-b border-border py-10">
        <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
          <p className="text-primary text-sm font-semibold uppercase tracking-wider mb-1">
            Khám Phá
          </p>
          <h1 className="text-3xl sm:text-4xl font-bold text-foreground">
            Thực Đơn Gia Đình
          </h1>
          <p className="text-muted-foreground mt-2 max-w-lg">
            Tổng hợp các công thức nấu ăn gia đình truyền thống Việt Nam, từ đơn giản đến
            cầu kỳ.
          </p>
        </div>
      </div>

      <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* Search & Filter Bar */}
        <div className="flex flex-col sm:flex-row gap-3 mb-6">
          {/* Search */}
          <div className="relative flex-1">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
            <input
              type="search"
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              placeholder="Tìm kiếm món ăn, nguyên liệu..."
              className="w-full pl-10 pr-4 py-2.5 border border-border rounded-xl bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
            />
          </div>

          {/* Toggle Filter */}
          <button
            onClick={() => setShowFilters(!showFilters)}
            className={`flex items-center gap-2 px-4 py-2.5 rounded-xl border text-sm font-medium transition-all ${
              showFilters
                ? "bg-primary text-primary-foreground border-primary"
                : "bg-background text-foreground border-border hover:border-primary hover:text-primary"
            }`}
          >
            <SlidersHorizontal className="w-4 h-4" />
            Bộ Lọc
            {hasActiveFilters && (
              <span className="w-5 h-5 bg-amber-500 text-white text-xs rounded-full flex items-center justify-center">
                !
              </span>
            )}
          </button>

          {hasActiveFilters && (
            <button
              onClick={clearFilters}
              className="flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-border text-muted-foreground hover:text-destructive hover:border-destructive text-sm transition-all"
            >
              <X className="w-4 h-4" />
              Xóa lọc
            </button>
          )}
        </div>

        {/* Filters Panel */}
        {showFilters && (
          <div className="bg-card border border-border rounded-2xl p-5 mb-6 space-y-4">
            {/* Category */}
            <div>
              <p className="text-sm font-semibold text-foreground mb-2">Danh Mục</p>
              <div className="flex flex-wrap gap-2">
                {categories.map((cat) => (
                  <button
                    key={cat}
                    onClick={() => setSelectedCategory(cat)}
                    className={`px-3.5 py-1.5 rounded-full text-sm font-medium transition-all ${
                      selectedCategory === cat
                        ? "bg-primary text-primary-foreground"
                        : "bg-muted text-muted-foreground hover:bg-primary/10 hover:text-primary"
                    }`}
                  >
                    {cat}
                  </button>
                ))}
              </div>
            </div>

            {/* Difficulty */}
            <div>
              <p className="text-sm font-semibold text-foreground mb-2">Độ Khó</p>
              <div className="flex flex-wrap gap-2">
                {difficulties.map((diff) => (
                  <button
                    key={diff}
                    onClick={() => setSelectedDifficulty(diff)}
                    className={`px-3.5 py-1.5 rounded-full text-sm font-medium transition-all ${
                      selectedDifficulty === diff
                        ? "bg-primary text-primary-foreground"
                        : "bg-muted text-muted-foreground hover:bg-primary/10 hover:text-primary"
                    }`}
                  >
                    {diff}
                  </button>
                ))}
              </div>
            </div>
          </div>
        )}

        {/* Category Quick Links (always visible) */}
        <div className="flex gap-2 overflow-x-auto pb-2 mb-6 -mx-1 px-1">
          {categories.map((cat) => (
            <button
              key={cat}
              onClick={() => setSelectedCategory(cat)}
              className={`whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition-all flex-shrink-0 ${
                selectedCategory === cat
                  ? "bg-primary text-primary-foreground shadow-sm"
                  : "bg-card border border-border text-muted-foreground hover:border-primary hover:text-primary"
              }`}
            >
              {cat}
            </button>
          ))}
        </div>

        {/* Results count */}
        <p className="text-sm text-muted-foreground mb-6">
          {filtered.length === dishes.length
            ? `Tất cả ${dishes.length} món ăn`
            : `${filtered.length} món ăn được tìm thấy`}
        </p>

        {/* Grid */}
        {filtered.length > 0 ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            {filtered.map((dish) => (
              <DishCard key={dish.id} dish={dish} />
            ))}
          </div>
        ) : (
          <div className="text-center py-20">
            <div className="text-6xl mb-4">🍽️</div>
            <h3 className="text-xl font-semibold text-foreground mb-2">
              Không tìm thấy món ăn
            </h3>
            <p className="text-muted-foreground mb-6">
              Hãy thử tìm kiếm với từ khóa khác hoặc bỏ bộ lọc.
            </p>
            <button
              onClick={clearFilters}
              className="inline-flex items-center gap-2 bg-primary text-primary-foreground px-5 py-2.5 rounded-xl font-medium hover:bg-primary/90 transition-colors"
            >
              <X className="w-4 h-4" />
              Xóa bộ lọc
            </button>
          </div>
        )}
      </div>
    </div>
  );
}
