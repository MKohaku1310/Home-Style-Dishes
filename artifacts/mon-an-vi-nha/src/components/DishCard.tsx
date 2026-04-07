import { Link } from "wouter";
import { Clock, Users, ChefHat } from "lucide-react";
import type { Dish } from "@/data/dishes";

interface DishCardProps {
  dish: Dish;
}

const difficultyColor = {
  Dễ: "bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400",
  "Trung bình": "bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400",
  Khó: "bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400",
};

export default function DishCard({ dish }: DishCardProps) {
  return (
    <Link href={`/dish/${dish.slug}`}>
      <div className="group bg-card border border-border rounded-2xl overflow-hidden cursor-pointer hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
        {/* Image */}
        <div className="relative overflow-hidden aspect-video">
          <img
            src={dish.image}
            alt={dish.name}
            className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
            loading="lazy"
          />
          <div className="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
          {/* Category badge */}
          <span className="absolute top-3 left-3 bg-background/90 backdrop-blur-sm text-foreground text-xs font-medium px-2.5 py-1 rounded-full border border-border">
            {dish.category}
          </span>
          {/* Difficulty badge */}
          <span
            className={`absolute top-3 right-3 text-xs font-medium px-2.5 py-1 rounded-full ${difficultyColor[dish.difficulty]}`}
          >
            {dish.difficulty}
          </span>
        </div>

        {/* Content */}
        <div className="p-4">
          <h3 className="text-base font-semibold text-foreground mb-2 group-hover:text-primary transition-colors line-clamp-1">
            {dish.name}
          </h3>
          <p className="text-muted-foreground text-sm leading-relaxed line-clamp-2 mb-3">
            {dish.description}
          </p>

          {/* Meta */}
          <div className="flex items-center gap-3 text-xs text-muted-foreground">
            <span className="flex items-center gap-1">
              <Clock className="w-3.5 h-3.5" />
              {dish.prepTime + dish.cookTime} phút
            </span>
            <span className="flex items-center gap-1">
              <Users className="w-3.5 h-3.5" />
              {dish.servings} người
            </span>
            <span className="flex items-center gap-1">
              <ChefHat className="w-3.5 h-3.5" />
              {dish.ingredients.length} nguyên liệu
            </span>
          </div>
        </div>
      </div>
    </Link>
  );
}
