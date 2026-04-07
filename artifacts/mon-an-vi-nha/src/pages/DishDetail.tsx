import { useRoute, Link } from "wouter";
import { Clock, Users, ChefHat, ArrowLeft, CheckCircle2, Tag } from "lucide-react";
import { dishes } from "@/data/dishes";
import DishCard from "@/components/DishCard";

const difficultyColor = {
  Dễ: "bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400",
  "Trung bình": "bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400",
  Khó: "bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400",
};

export default function DishDetail() {
  const [, params] = useRoute("/dish/:slug");
  const slug = params?.slug;
  const dish = dishes.find((d) => d.slug === slug);

  if (!dish) {
    return (
      <div className="min-h-screen flex flex-col items-center justify-center pt-20 px-4 text-center">
        <div className="text-6xl mb-4">🍽️</div>
        <h1 className="text-2xl font-bold text-foreground mb-2">Không tìm thấy món ăn</h1>
        <p className="text-muted-foreground mb-6">
          Món ăn này không tồn tại hoặc đã bị xóa.
        </p>
        <Link
          href="/menu"
          className="inline-flex items-center gap-2 bg-primary text-primary-foreground px-5 py-2.5 rounded-xl font-medium hover:bg-primary/90 transition-colors"
        >
          <ArrowLeft className="w-4 h-4" />
          Quay lại thực đơn
        </Link>
      </div>
    );
  }

  const relatedDishes = dishes
    .filter((d) => d.id !== dish.id && d.category === dish.category)
    .slice(0, 3);

  return (
    <div className="min-h-screen pt-20 pb-16">
      {/* Hero Image */}
      <div className="relative h-72 sm:h-96 lg:h-[480px] overflow-hidden">
        <img
          src={dish.image}
          alt={dish.name}
          className="w-full h-full object-cover"
        />
        <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent" />

        {/* Back Button */}
        <div className="absolute top-4 left-4 sm:left-8">
          <Link
            href="/menu"
            className="inline-flex items-center gap-2 bg-black/40 hover:bg-black/60 backdrop-blur-sm text-white px-4 py-2 rounded-xl text-sm font-medium transition-all"
          >
            <ArrowLeft className="w-4 h-4" />
            Thực đơn
          </Link>
        </div>

        {/* Title overlay */}
        <div className="absolute bottom-0 left-0 right-0 p-6 sm:p-8">
          <div className="max-w-4xl mx-auto">
            <div className="flex flex-wrap gap-2 mb-3">
              <span className="bg-background/90 backdrop-blur-sm text-foreground text-xs font-medium px-3 py-1 rounded-full border border-border">
                {dish.category}
              </span>
              <span
                className={`text-xs font-medium px-3 py-1 rounded-full ${difficultyColor[dish.difficulty]}`}
              >
                {dish.difficulty}
              </span>
            </div>
            <h1 className="text-3xl sm:text-4xl font-bold text-white">{dish.name}</h1>
          </div>
        </div>
      </div>

      {/* Content */}
      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* Meta Info */}
        <div className="grid grid-cols-3 gap-4 mb-8 p-4 bg-card border border-border rounded-2xl">
          <div className="text-center">
            <div className="flex items-center justify-center gap-1.5 text-primary mb-1">
              <Clock className="w-4 h-4" />
            </div>
            <div className="text-lg font-bold text-foreground">
              {dish.prepTime + dish.cookTime} phút
            </div>
            <div className="text-xs text-muted-foreground">Tổng thời gian</div>
          </div>
          <div className="text-center border-x border-border">
            <div className="flex items-center justify-center gap-1.5 text-primary mb-1">
              <Users className="w-4 h-4" />
            </div>
            <div className="text-lg font-bold text-foreground">{dish.servings} người</div>
            <div className="text-xs text-muted-foreground">Khẩu phần</div>
          </div>
          <div className="text-center">
            <div className="flex items-center justify-center gap-1.5 text-primary mb-1">
              <ChefHat className="w-4 h-4" />
            </div>
            <div className="text-lg font-bold text-foreground">{dish.ingredients.length}</div>
            <div className="text-xs text-muted-foreground">Nguyên liệu</div>
          </div>
        </div>

        {/* Time breakdown */}
        <div className="flex gap-3 mb-8 text-sm">
          <div className="bg-muted/50 rounded-xl px-4 py-2.5">
            <span className="text-muted-foreground">Sơ chế: </span>
            <span className="font-semibold text-foreground">{dish.prepTime} phút</span>
          </div>
          <div className="bg-muted/50 rounded-xl px-4 py-2.5">
            <span className="text-muted-foreground">Nấu: </span>
            <span className="font-semibold text-foreground">{dish.cookTime} phút</span>
          </div>
        </div>

        {/* Description */}
        <div className="mb-10">
          <h2 className="text-xl font-bold text-foreground mb-3">Giới Thiệu</h2>
          <p className="text-muted-foreground leading-relaxed text-base">{dish.description}</p>
        </div>

        <div className="grid md:grid-cols-2 gap-8 mb-10">
          {/* Ingredients */}
          <div>
            <h2 className="text-xl font-bold text-foreground mb-4 flex items-center gap-2">
              <span className="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary">
                🥬
              </span>
              Nguyên Liệu
            </h2>
            <ul className="space-y-2">
              {dish.ingredients.map((ingredient, idx) => (
                <li
                  key={idx}
                  className="flex items-start gap-3 p-3 bg-card border border-border rounded-xl hover:border-primary/40 transition-colors"
                >
                  <CheckCircle2 className="w-4 h-4 text-primary mt-0.5 flex-shrink-0" />
                  <span className="text-foreground text-sm">{ingredient}</span>
                </li>
              ))}
            </ul>
          </div>

          {/* Steps */}
          <div>
            <h2 className="text-xl font-bold text-foreground mb-4 flex items-center gap-2">
              <span className="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary">
                👨‍🍳
              </span>
              Cách Nấu
            </h2>
            <ol className="space-y-3">
              {dish.steps.map((step, idx) => (
                <li key={idx} className="flex gap-3">
                  <span className="flex-shrink-0 w-7 h-7 bg-primary text-primary-foreground rounded-full text-xs font-bold flex items-center justify-center mt-0.5">
                    {idx + 1}
                  </span>
                  <p className="text-foreground text-sm leading-relaxed pt-0.5">{step}</p>
                </li>
              ))}
            </ol>
          </div>
        </div>

        {/* Tags */}
        {dish.tags.length > 0 && (
          <div className="mb-10">
            <div className="flex items-center gap-2 flex-wrap">
              <Tag className="w-4 h-4 text-muted-foreground" />
              {dish.tags.map((tag) => (
                <span
                  key={tag}
                  className="bg-muted text-muted-foreground text-xs px-3 py-1.5 rounded-full hover:bg-primary/10 hover:text-primary transition-colors cursor-default"
                >
                  #{tag}
                </span>
              ))}
            </div>
          </div>
        )}

        {/* Related */}
        {relatedDishes.length > 0 && (
          <div>
            <h2 className="text-xl font-bold text-foreground mb-5">
              Món Cùng Danh Mục
            </h2>
            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
              {relatedDishes.map((d) => (
                <DishCard key={d.id} dish={d} />
              ))}
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
