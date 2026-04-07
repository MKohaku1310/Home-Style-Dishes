import { Link } from "wouter";
import { Clock, Users } from "lucide-react";
import type { Dish } from "@/data/dishes";
import { categoryLabels } from "@/data/dishes";

interface DishCardProps {
  dish: Dish;
  featured?: boolean;
}

export default function DishCard({ dish, featured = false }: DishCardProps) {
  return (
    <Link href={`/dish/${dish.slug}`}>
      <article className={`dish-card${featured ? " dish-card--featured" : ""}`}>
        <div className="dish-card__img-wrap">
          <img
            src={dish.image}
            alt={dish.name}
            className="sepia-image"
            loading="lazy"
          />
        </div>
        <div className="dish-card__body">
          <span className="category-badge">{categoryLabels[dish.category]}</span>
          <h3 className="dish-card__name">{dish.name}</h3>
          <p className="dish-card__desc">{dish.description}</p>
          <div className="dish-card__meta">
            <span style={{ display: "flex", alignItems: "center", gap: "0.3rem" }}>
              <Clock size={11} />
              {dish.cookTime}
            </span>
            <span style={{ display: "flex", alignItems: "center", gap: "0.3rem" }}>
              <Users size={11} />
              {dish.servings} người
            </span>
            <span
              style={{
                color:
                  dish.difficulty === "Dễ"
                    ? "var(--color-green)"
                    : dish.difficulty === "Trung bình"
                    ? "var(--color-gold)"
                    : "var(--color-red)",
              }}
            >
              {dish.difficulty}
            </span>
          </div>
        </div>
      </article>
    </Link>
  );
}
