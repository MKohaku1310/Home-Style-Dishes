import { Link } from "wouter";
import { ChefHat, Heart, Facebook, Instagram, Youtube } from "lucide-react";

export default function Footer() {
  return (
    <footer className="bg-card border-t border-border mt-16">
      <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {/* Brand */}
          <div>
            <div className="flex items-center gap-2 mb-4">
              <div className="w-9 h-9 rounded-full bg-primary flex items-center justify-center">
                <ChefHat className="w-5 h-5 text-primary-foreground" />
              </div>
              <span className="text-xl font-bold text-foreground">Vị Nhà</span>
            </div>
            <p className="text-muted-foreground text-sm leading-relaxed">
              Mang hương vị bếp nhà đến với mọi gia đình. Nơi lưu giữ những
              công thức nấu ăn truyền thống của ẩm thực Việt Nam.
            </p>
            <div className="flex items-center gap-3 mt-4">
              <a
                href="#"
                className="w-9 h-9 rounded-full bg-muted flex items-center justify-center text-muted-foreground hover:bg-primary hover:text-primary-foreground transition-all"
                aria-label="Facebook"
              >
                <Facebook className="w-4 h-4" />
              </a>
              <a
                href="#"
                className="w-9 h-9 rounded-full bg-muted flex items-center justify-center text-muted-foreground hover:bg-primary hover:text-primary-foreground transition-all"
                aria-label="Instagram"
              >
                <Instagram className="w-4 h-4" />
              </a>
              <a
                href="#"
                className="w-9 h-9 rounded-full bg-muted flex items-center justify-center text-muted-foreground hover:bg-primary hover:text-primary-foreground transition-all"
                aria-label="Youtube"
              >
                <Youtube className="w-4 h-4" />
              </a>
            </div>
          </div>

          {/* Links */}
          <div>
            <h3 className="text-foreground font-semibold mb-4">Khám Phá</h3>
            <ul className="space-y-2">
              {[
                { href: "/", label: "Trang Chủ" },
                { href: "/menu", label: "Thực Đơn" },
                { href: "/about", label: "Giới Thiệu" },
                { href: "/contact", label: "Liên Hệ" },
              ].map((link) => (
                <li key={link.href}>
                  <Link
                    href={link.href}
                    className="text-muted-foreground hover:text-primary text-sm transition-colors"
                  >
                    {link.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Categories */}
          <div>
            <h3 className="text-foreground font-semibold mb-4">Danh Mục</h3>
            <ul className="space-y-2">
              {["Canh & Súp", "Kho & Rim", "Xào & Chiên", "Luộc & Hấp", "Cơm & Cháo"].map(
                (cat) => (
                  <li key={cat}>
                    <Link
                      href={`/menu?category=${encodeURIComponent(cat)}`}
                      className="text-muted-foreground hover:text-primary text-sm transition-colors"
                    >
                      {cat}
                    </Link>
                  </li>
                )
              )}
            </ul>
          </div>
        </div>

        <div className="border-t border-border mt-8 pt-8 flex flex-col sm:flex-row items-center justify-between gap-3">
          <p className="text-muted-foreground text-sm">
            © 2025 Vị Nhà. Tất cả quyền được bảo lưu.
          </p>
          <p className="text-muted-foreground text-sm flex items-center gap-1">
            Làm bằng <Heart className="w-3.5 h-3.5 text-red-500 fill-red-500" /> cho bữa cơm
            nhà
          </p>
        </div>
      </div>
    </footer>
  );
}
