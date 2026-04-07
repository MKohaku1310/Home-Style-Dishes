import { Link } from "wouter";
import { ArrowRight, Star, Clock, Users } from "lucide-react";
import DishCard from "@/components/DishCard";
import { dishes } from "@/data/dishes";

const featuredDishes = dishes.slice(0, 3);

const stats = [
  { label: "Công Thức", value: "50+" },
  { label: "Danh Mục", value: "5" },
  { label: "Người Nấu", value: "1K+" },
  { label: "Đánh Giá", value: "4.9★" },
];

export default function Home() {
  return (
    <div className="min-h-screen">
      {/* Hero Section */}
      <section className="relative min-h-screen flex items-center overflow-hidden">
        {/* Background */}
        <div
          className="absolute inset-0 z-0"
          style={{
            backgroundImage:
              "url('https://images.unsplash.com/photo-1555126634-323283e090fa?w=1600&q=80')",
            backgroundSize: "cover",
            backgroundPosition: "center",
          }}
        >
          <div className="absolute inset-0 bg-black/55 dark:bg-black/70" />
        </div>

        {/* Content */}
        <div className="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-16">
          <div className="max-w-2xl">
            <span className="inline-block bg-primary/20 border border-primary/40 text-primary-foreground backdrop-blur-sm text-sm font-medium px-4 py-1.5 rounded-full mb-6">
              🍚 Ẩm Thực Gia Đình Việt Nam
            </span>
            <h1 className="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
              Hương Vị{" "}
              <span className="text-amber-400">Bếp Nhà</span>{" "}
              Ngày Xưa
            </h1>
            <p className="text-lg text-white/80 leading-relaxed mb-8 max-w-lg">
              Khám phá những công thức nấu ăn truyền thống của ẩm thực Việt Nam.
              Từ bữa cơm bình dị đến những món đặc trưng ngày lễ — tất cả đều
              mang đậm hương vị bếp nhà yêu thương.
            </p>
            <div className="flex flex-wrap gap-4">
              <Link
                href="/menu"
                className="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-primary-foreground px-6 py-3 rounded-xl font-semibold transition-all hover:scale-105 shadow-lg"
              >
                Khám Phá Thực Đơn
                <ArrowRight className="w-4 h-4" />
              </Link>
              <Link
                href="/about"
                className="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white backdrop-blur-sm border border-white/30 px-6 py-3 rounded-xl font-semibold transition-all"
              >
                Tìm Hiểu Thêm
              </Link>
            </div>
          </div>

          {/* Stats */}
          <div className="mt-16 grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-lg sm:max-w-full">
            {stats.map((stat) => (
              <div
                key={stat.label}
                className="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl p-4 text-center"
              >
                <div className="text-2xl font-bold text-white">{stat.value}</div>
                <div className="text-white/70 text-sm mt-1">{stat.label}</div>
              </div>
            ))}
          </div>
        </div>

        {/* Scroll indicator */}
        <div className="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 flex flex-col items-center gap-2 animate-bounce">
          <div className="w-px h-10 bg-white/40" />
          <span className="text-white/50 text-xs">Cuộn xuống</span>
        </div>
      </section>

      {/* About Banner */}
      <section className="bg-primary/5 border-y border-border py-12">
        <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex flex-col md:flex-row items-center gap-8">
            <div className="flex-1">
              <h2 className="text-2xl font-bold text-foreground mb-3">
                Tại Sao Chọn Vị Nhà?
              </h2>
              <p className="text-muted-foreground leading-relaxed">
                Mỗi công thức đều được chọn lọc kỹ lưỡng, trình bày rõ ràng từng bước,
                với nguyên liệu dễ tìm để bạn có thể nấu ngay tại nhà.
              </p>
            </div>
            <div className="flex flex-wrap gap-4 md:justify-end">
              {[
                { icon: <Star className="w-5 h-5" />, text: "Công thức chuẩn vị" },
                { icon: <Clock className="w-5 h-5" />, text: "Hướng dẫn từng bước" },
                { icon: <Users className="w-5 h-5" />, text: "Dành cho mọi gia đình" },
              ].map((item) => (
                <div
                  key={item.text}
                  className="flex items-center gap-2 bg-background border border-border rounded-xl px-4 py-3 text-sm font-medium text-foreground"
                >
                  <span className="text-primary">{item.icon}</span>
                  {item.text}
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Featured Dishes */}
      <section className="py-16">
        <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-end justify-between mb-10">
            <div>
              <p className="text-primary text-sm font-semibold uppercase tracking-wider mb-1">
                Nổi Bật Tuần Này
              </p>
              <h2 className="text-3xl font-bold text-foreground">Món Ăn Được Yêu Thích</h2>
            </div>
            <Link
              href="/menu"
              className="hidden sm:flex items-center gap-1.5 text-primary hover:text-primary/80 font-medium text-sm transition-colors"
            >
              Xem tất cả
              <ArrowRight className="w-4 h-4" />
            </Link>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {featuredDishes.map((dish) => (
              <DishCard key={dish.id} dish={dish} />
            ))}
          </div>

          <div className="mt-8 text-center sm:hidden">
            <Link
              href="/menu"
              className="inline-flex items-center gap-2 text-primary hover:text-primary/80 font-medium"
            >
              Xem tất cả món ăn
              <ArrowRight className="w-4 h-4" />
            </Link>
          </div>
        </div>
      </section>

      {/* Categories Section */}
      <section className="py-16 bg-muted/30">
        <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-10">
            <p className="text-primary text-sm font-semibold uppercase tracking-wider mb-1">
              Dễ Tìm Món Ngon
            </p>
            <h2 className="text-3xl font-bold text-foreground">Danh Mục Món Ăn</h2>
          </div>
          <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            {[
              { name: "Canh & Súp", emoji: "🍲", count: dishes.filter((d) => d.category === "Canh & Súp").length },
              { name: "Kho & Rim", emoji: "🥘", count: dishes.filter((d) => d.category === "Kho & Rim").length },
              { name: "Xào & Chiên", emoji: "🍳", count: dishes.filter((d) => d.category === "Xào & Chiên").length },
              { name: "Luộc & Hấp", emoji: "🫕", count: dishes.filter((d) => d.category === "Luộc & Hấp").length },
              { name: "Cơm & Cháo", emoji: "🍚", count: dishes.filter((d) => d.category === "Cơm & Cháo").length },
            ].map((cat) => (
              <Link
                key={cat.name}
                href={`/menu?category=${encodeURIComponent(cat.name)}`}
                className="bg-card border border-border rounded-2xl p-4 text-center hover:border-primary hover:bg-primary/5 hover:-translate-y-1 transition-all duration-200 group cursor-pointer"
              >
                <div className="text-3xl mb-2">{cat.emoji}</div>
                <div className="text-sm font-semibold text-foreground group-hover:text-primary transition-colors">
                  {cat.name}
                </div>
                <div className="text-xs text-muted-foreground mt-1">{cat.count} món</div>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-16">
        <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
          <div
            className="rounded-3xl overflow-hidden relative p-8 sm:p-12 text-center"
            style={{
              backgroundImage:
                "url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1200&q=80')",
              backgroundSize: "cover",
              backgroundPosition: "center",
            }}
          >
            <div className="absolute inset-0 bg-black/60 dark:bg-black/75" />
            <div className="relative z-10">
              <h2 className="text-3xl sm:text-4xl font-bold text-white mb-4">
                Bắt Đầu Nấu Ăn Ngay Hôm Nay
              </h2>
              <p className="text-white/80 text-lg mb-8 max-w-lg mx-auto">
                Hàng chục công thức dễ làm đang chờ bạn khám phá. Bữa cơm ấm áp
                chỉ cách vài bước chân vào bếp.
              </p>
              <Link
                href="/menu"
                className="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-white px-8 py-4 rounded-xl font-bold text-lg transition-all hover:scale-105 shadow-lg shadow-amber-500/30"
              >
                Vào Bếp Ngay
                <ArrowRight className="w-5 h-5" />
              </Link>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
