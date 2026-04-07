import { Link } from "wouter";
import { Heart, BookOpen, Users, Leaf, ArrowRight } from "lucide-react";

const values = [
  {
    icon: <Heart className="w-6 h-6" />,
    title: "Yêu Thương Từ Bếp",
    description:
      "Mỗi bữa cơm là cơ hội để bày tỏ tình yêu thương với gia đình. Chúng tôi tin rằng nấu ăn không chỉ là công việc — đó là ngôn ngữ của tình thương.",
  },
  {
    icon: <BookOpen className="w-6 h-6" />,
    title: "Lưu Giữ Truyền Thống",
    description:
      "Những công thức được truyền từ đời này sang đời khác là kho báu văn hóa. Chúng tôi cam kết ghi chép và bảo tồn hương vị ẩm thực Việt Nam.",
  },
  {
    icon: <Users className="w-6 h-6" />,
    title: "Dành Cho Mọi Người",
    description:
      "Dù bạn là người mới học nấu hay đầu bếp có kinh nghiệm, chúng tôi đều có công thức phù hợp. Hướng dẫn rõ ràng, dễ hiểu, dễ làm.",
  },
  {
    icon: <Leaf className="w-6 h-6" />,
    title: "Nguyên Liệu Tươi Sạch",
    description:
      "Chúng tôi khuyến khích sử dụng nguyên liệu tươi, sạch, có nguồn gốc rõ ràng. Bữa ăn ngon nhất là bữa ăn tốt cho sức khỏe của cả gia đình.",
  },
];

const team = [
  {
    name: "Nguyễn Thị Hoa",
    role: "Đầu Bếp Chính",
    bio: "20 năm kinh nghiệm nấu ăn gia đình và nhà hàng. Đặc biệt yêu thích các món ăn miền Nam.",
    avatar: "https://images.unsplash.com/photo-1607631568010-a87245c0daf8?w=200&q=80",
  },
  {
    name: "Trần Văn Nam",
    role: "Chuyên Gia Ẩm Thực",
    bio: "Nghiên cứu ẩm thực truyền thống Việt Nam. Đã xuất bản 2 cuốn sách dạy nấu ăn.",
    avatar: "https://images.unsplash.com/photo-1622021142947-da7dedc7c39a?w=200&q=80",
  },
  {
    name: "Lê Thị Mai",
    role: "Biên Soạn Công Thức",
    bio: "Đam mê ẩm thực và viết lách. Chuyên sưu tầm các công thức từ các vùng miền Việt Nam.",
    avatar: "https://images.unsplash.com/photo-1594744803329-e58b31de8bf5?w=200&q=80",
  },
];

export default function About() {
  return (
    <div className="min-h-screen pt-20 pb-16">
      {/* Hero */}
      <div
        className="relative py-24 overflow-hidden"
        style={{
          backgroundImage:
            "url('https://images.unsplash.com/photo-1466637574441-749b8f19452f?w=1400&q=80')",
          backgroundSize: "cover",
          backgroundPosition: "center",
        }}
      >
        <div className="absolute inset-0 bg-black/60" />
        <div className="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 text-center">
          <h1 className="text-4xl sm:text-5xl font-bold text-white mb-4">
            Về Chúng Tôi
          </h1>
          <p className="text-xl text-white/80 max-w-2xl mx-auto">
            Vị Nhà ra đời từ tình yêu với ẩm thực gia đình và khát vọng gìn giữ
            những hương vị Việt Nam truyền thống
          </p>
        </div>
      </div>

      {/* Story */}
      <section className="py-16">
        <div className="max-w-4xl mx-auto px-4 sm:px-6">
          <div className="grid md:grid-cols-2 gap-12 items-center">
            <div>
              <p className="text-primary text-sm font-semibold uppercase tracking-wider mb-2">
                Câu Chuyện Của Chúng Tôi
              </p>
              <h2 className="text-3xl font-bold text-foreground mb-6">
                Hương Vị Quê Nhà Trong Từng Món Ăn
              </h2>
              <div className="space-y-4 text-muted-foreground leading-relaxed">
                <p>
                  Vị Nhà được thành lập năm 2023 với một sứ mệnh đơn giản: lưu giữ và
                  chia sẻ những công thức nấu ăn gia đình truyền thống của người Việt.
                </p>
                <p>
                  Trong thời đại bận rộn ngày nay, nhiều gia đình không còn nhiều thời
                  gian nấu ăn. Nhưng chúng tôi tin rằng bữa cơm gia đình vẫn là điều
                  thiêng liêng và quan trọng nhất — không gian để mọi người quây quần,
                  chia sẻ và yêu thương nhau.
                </p>
                <p>
                  Từ những món canh mẹ nấu, nồi cơm ấm nghi ngút khói, đến những món
                  kho đặc trưng ngày Tết — tất cả đều được chúng tôi ghi chép tỉ mỉ
                  để bạn có thể tái hiện ngay tại bếp nhà mình.
                </p>
              </div>
            </div>
            <div className="relative">
              <img
                src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=600&q=80"
                alt="Bếp nhà Việt Nam"
                className="rounded-2xl w-full object-cover aspect-square shadow-xl"
              />
              <div className="absolute -bottom-4 -right-4 bg-primary text-primary-foreground rounded-2xl p-4 shadow-lg">
                <div className="text-2xl font-bold">2023</div>
                <div className="text-sm opacity-90">Thành lập</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Values */}
      <section className="py-16 bg-muted/30">
        <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <p className="text-primary text-sm font-semibold uppercase tracking-wider mb-2">
              Giá Trị Cốt Lõi
            </p>
            <h2 className="text-3xl font-bold text-foreground">
              Điều Chúng Tôi Tin Tưởng
            </h2>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {values.map((value) => (
              <div
                key={value.title}
                className="bg-card border border-border rounded-2xl p-6 hover:border-primary/40 hover:-translate-y-1 transition-all duration-200"
              >
                <div className="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center text-primary mb-4">
                  {value.icon}
                </div>
                <h3 className="text-foreground font-semibold mb-2">{value.title}</h3>
                <p className="text-muted-foreground text-sm leading-relaxed">
                  {value.description}
                </p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Team */}
      <section className="py-16">
        <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <p className="text-primary text-sm font-semibold uppercase tracking-wider mb-2">
              Đội Ngũ
            </p>
            <h2 className="text-3xl font-bold text-foreground">Những Người Đằng Sau Bếp</h2>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-8">
            {team.map((member) => (
              <div key={member.name} className="text-center">
                <img
                  src={member.avatar}
                  alt={member.name}
                  className="w-24 h-24 rounded-full object-cover mx-auto mb-4 border-4 border-border shadow-md"
                />
                <h3 className="text-foreground font-bold text-lg">{member.name}</h3>
                <p className="text-primary text-sm font-medium mb-2">{member.role}</p>
                <p className="text-muted-foreground text-sm leading-relaxed">{member.bio}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="py-12">
        <div className="max-w-2xl mx-auto px-4 text-center">
          <h2 className="text-2xl font-bold text-foreground mb-4">
            Sẵn Sàng Vào Bếp Chưa?
          </h2>
          <p className="text-muted-foreground mb-6">
            Khám phá hàng chục công thức nấu ăn truyền thống đang chờ bạn.
          </p>
          <Link
            href="/menu"
            className="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-primary-foreground px-6 py-3 rounded-xl font-semibold transition-all hover:scale-105"
          >
            Xem Thực Đơn
            <ArrowRight className="w-4 h-4" />
          </Link>
        </div>
      </section>
    </div>
  );
}
