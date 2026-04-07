export interface Ingredient {
  amount: string;
  name: string;
}

export interface Step {
  step: number;
  title: string;
  instruction: string;
}

export interface Dish {
  id: number;
  name: string;
  slug: string;
  category: "canh" | "kho" | "xao" | "luoc" | "com" | "bun";
  region: string;
  description: string;
  image: string;
  prepTime: string;
  cookTime: string;
  servings: number;
  difficulty: "Dễ" | "Trung bình" | "Khó";
  ingredients: Ingredient[];
  steps: Step[];
  story: string;
}

export const categoryLabels: Record<string, string> = {
  canh: "Canh & Súp",
  kho: "Kho & Rim",
  xao: "Xào & Chiên",
  luoc: "Luộc & Hấp",
  com: "Cơm",
  bun: "Bún & Phở",
};

export const dishes: Dish[] = [
  {
    id: 1,
    name: "Canh Chua Cá Lóc",
    slug: "canh-chua-ca-loc",
    category: "canh",
    region: "Nam Bộ",
    description:
      "Canh chua cá lóc là linh hồn của bếp nhà miền Nam — vị chua thanh từ me chín, ngọt ngào từ cá đồng tươi, quyện cùng hương thơm ngò om và bạc hà. Mỗi tô canh là một buổi chiều quê nhà, khói bếp vấn vương.",
    image: "https://images.unsplash.com/photo-1569050467447-ce54b3bbc37d?w=800&q=80",
    prepTime: "20 phút",
    cookTime: "30 phút",
    servings: 4,
    difficulty: "Trung bình",
    ingredients: [
      { amount: "500g", name: "cá lóc, làm sạch cắt khúc" },
      { amount: "100g", name: "me chua" },
      { amount: "2 trái", name: "cà chua chín" },
      { amount: "1/2 quả", name: "dứa (thơm)" },
      { amount: "200g", name: "đậu bắp" },
      { amount: "100g", name: "giá đỗ" },
      { amount: "2 cây", name: "bạc hà (dọc mùng)" },
      { amount: "1 mớ", name: "ngò om, ngò gai" },
      { amount: "3 muỗng canh", name: "nước mắm ngon" },
      { amount: "1 muỗng canh", name: "đường" },
      { amount: "vừa đủ", name: "muối, tiêu, ớt" },
    ],
    steps: [
      {
        step: 1,
        title: "Nấu nước me",
        instruction:
          "Ngâm me trong 200ml nước nóng khoảng 10 phút, bóp tan rồi lọc qua rây lấy nước cốt me, bỏ xác.",
      },
      {
        step: 2,
        title: "Sơ chế cá",
        instruction:
          "Cá lóc cắt khúc vừa ăn, rửa sạch với muối và gừng để khử mùi tanh, để ráo.",
      },
      {
        step: 3,
        title: "Chuẩn bị rau củ",
        instruction:
          "Cà chua cắt múi cau. Dứa cắt miếng vừa. Đậu bắp cắt đôi chéo. Bạc hà cắt khúc 5cm.",
      },
      {
        step: 4,
        title: "Nấu canh",
        instruction:
          "Đun sôi 1.5 lít nước, cho nước me vào. Nêm nước mắm, đường, muối vừa ăn. Cho cà chua và dứa vào đun 5 phút.",
      },
      {
        step: 5,
        title: "Cho cá vào",
        instruction:
          "Thả cá lóc vào nồi, nấu lửa vừa khoảng 10 phút đến khi cá chín mềm. Vớt bỏ bọt trắng.",
      },
      {
        step: 6,
        title: "Hoàn thiện",
        instruction:
          "Cho đậu bắp, bạc hà, giá đỗ vào đun thêm 3 phút. Tắt bếp, rắc ngò om, ngò gai lên trên. Ăn nóng với cơm trắng.",
      },
    ],
    story:
      "Ngày còn nhỏ, cứ mỗi chiều mưa, mẹ lại ra sau vườn hái ngò om và bẻ vài trái đậu bắp. Mùi canh chua sôi sùng sục từ trong bếp bay ra tận sân, kéo cả nhà vào bàn dù chưa đến giờ ăn. Canh chua không cần cá to hay nguyên liệu sang — chỉ cần bàn tay quen thuộc của người nấu là đã đủ ngon.",
  },
  {
    id: 2,
    name: "Thịt Kho Trứng",
    slug: "thit-kho-trung",
    category: "kho",
    region: "Nam Bộ",
    description:
      "Thịt kho trứng — hay còn gọi thịt kho tàu — là món ăn không thể vắng mặt trong mâm cơm ngày Tết. Nước dừa kho cùng thịt ba chỉ và trứng vịt tạo nên màu vàng óng ánh, vị mặn ngọt hòa quyện khiến cả nhà ăn không biết no.",
    image: "https://images.unsplash.com/photo-1547592166-23ac45744acd?w=800&q=80",
    prepTime: "15 phút",
    cookTime: "60 phút",
    servings: 4,
    difficulty: "Dễ",
    ingredients: [
      { amount: "600g", name: "thịt ba chỉ, cắt miếng 4cm" },
      { amount: "6 quả", name: "trứng vịt" },
      { amount: "500ml", name: "nước dừa tươi" },
      { amount: "3 muỗng canh", name: "nước mắm" },
      { amount: "2 muỗng canh", name: "đường thốt nốt (hoặc đường cát)" },
      { amount: "5 tép", name: "tỏi băm" },
      { amount: "2 nhánh", name: "hành lá" },
      { amount: "vừa đủ", name: "nước màu (nước hàng), tiêu" },
    ],
    steps: [
      {
        step: 1,
        title: "Sơ chế thịt",
        instruction:
          "Thịt ba chỉ trần qua nước sôi 2 phút để loại bỏ mùi hôi. Vớt ra, rửa sạch, cắt miếng vuông 4cm.",
      },
      {
        step: 2,
        title: "Ướp thịt",
        instruction:
          "Ướp thịt với nước mắm, đường, tỏi băm, tiêu và vài giọt nước màu. Để ngấm 20 phút.",
      },
      {
        step: 3,
        title: "Luộc trứng",
        instruction:
          "Luộc trứng vịt chín cứng (khoảng 10 phút), bóc vỏ. Ngâm vào nước lạnh cho dễ bóc và tránh vỡ lòng.",
      },
      {
        step: 4,
        title: "Kho thịt",
        instruction:
          "Phi thơm tỏi, cho thịt vào xào săn. Đổ nước dừa vào xâm xấp. Đun sôi, hớt bọt rồi hạ lửa nhỏ.",
      },
      {
        step: 5,
        title: "Cho trứng vào",
        instruction:
          "Cho trứng đã bóc vào nồi. Kho liu riu 40–50 phút, thỉnh thoảng trở nhẹ cho trứng vàng đều.",
      },
      {
        step: 6,
        title: "Hoàn thiện",
        instruction:
          "Khi nước kho sệt lại và thịt mềm trong, nêm lại cho vừa ăn. Rắc tiêu, rắc hành lá. Ăn với cơm trắng và dưa cải muối.",
      },
    ],
    story:
      "Nồi thịt kho Tết nhà nào cũng có, nhưng không nhà nào giống nhà nào. Nhà tôi kho bằng đường thốt nốt nên có vị ngọt thanh riêng. Sáng mùng một, nồi thịt kho được hâm lại — mùi thơm từ bếp nhà nội lan ra cả xóm. Đó là mùi Tết thực sự, không thứ nước hoa nào sánh được.",
  },
  {
    id: 3,
    name: "Cá Kho Tộ",
    slug: "ca-kho-to",
    category: "kho",
    region: "Toàn quốc",
    description:
      "Cá kho tộ trong nồi đất nung — món ăn bình dị mà đậm đà nhất của bữa cơm Việt. Nước kho sệt mặn ngọt thấm vào từng thớ cá, kết hợp hương gừng và tiêu đen — chỉ một miếng cũng đủ ăn hết chén cơm.",
    image: "https://images.unsplash.com/photo-1519984388953-d2406bc725e1?w=800&q=80",
    prepTime: "10 phút",
    cookTime: "45 phút",
    servings: 3,
    difficulty: "Dễ",
    ingredients: [
      { amount: "500g", name: "cá (cá thu, cá basa hoặc cá lóc)" },
      { amount: "3 muỗng canh", name: "nước mắm" },
      { amount: "2 muỗng canh", name: "đường" },
      { amount: "1 nhánh", name: "gừng tươi, đập dập" },
      { amount: "4 tép", name: "tỏi" },
      { amount: "2 quả", name: "ớt tươi" },
      { amount: "vừa đủ", name: "nước màu, tiêu xay, dầu ăn" },
    ],
    steps: [
      {
        step: 1,
        title: "Sơ chế cá",
        instruction: "Cá cắt khúc vừa ăn, ướp muối và tiêu 10 phút. Tỏi băm, gừng đập dập.",
      },
      {
        step: 2,
        title: "Pha nước kho",
        instruction: "Trộn nước mắm + đường + vài giọt nước màu. Nếm thử — phải vừa mặn vừa ngọt.",
      },
      {
        step: 3,
        title: "Phi thơm",
        instruction:
          "Cho dầu vào tộ đất, phi thơm tỏi và gừng. Xếp cá vào tộ, đổ hỗn hợp nước kho lên.",
      },
      {
        step: 4,
        title: "Kho cá",
        instruction:
          "Thêm 100ml nước vào, đun sôi lửa lớn 5 phút. Hạ lửa thật nhỏ, kho tiếp 30–35 phút.",
      },
      {
        step: 5,
        title: "Hoàn thiện",
        instruction:
          "Khi nước kho gần cạn, rắc tiêu và ớt lên trên. Tắt bếp, để nguội chút rồi ăn với cơm trắng nóng hổi.",
      },
    ],
    story:
      "Tộ cá kho của bà nội tôi lúc nào cũng đặt ở góc bếp, sáng nào cũng được hâm lên một lần. Qua ba ngày, nước kho cạn dần nhưng cá lại ngon hơn — vị đậm đà thêm lên theo từng giờ. Tôi không hiểu tại sao, chỉ biết rằng cá kho ngày thứ ba bao giờ cũng là ngon nhất.",
  },
  {
    id: 4,
    name: "Rau Muống Xào Tỏi",
    slug: "rau-muong-xao-toi",
    category: "xao",
    region: "Toàn quốc",
    description:
      "Rau muống xào tỏi — đơn giản nhất mà lại không thể thiếu trong mâm cơm Việt. Rau xanh giòn, mùi tỏi thơm lừng từ chảo nóng, vị vừa miệng là thứ đưa cơm hoàn hảo cho mọi bữa ăn gia đình.",
    image: "https://images.unsplash.com/photo-1540420773420-3366772f4999?w=800&q=80",
    prepTime: "10 phút",
    cookTime: "8 phút",
    servings: 3,
    difficulty: "Dễ",
    ingredients: [
      { amount: "500g", name: "rau muống, nhặt sạch" },
      { amount: "6 tép", name: "tỏi, đập dập băm nhỏ" },
      { amount: "2 muỗng canh", name: "dầu ăn" },
      { amount: "1 muỗng canh", name: "nước mắm" },
      { amount: "1 quả", name: "ớt tươi (tùy khẩu vị)" },
      { amount: "vừa đủ", name: "muối, tiêu" },
    ],
    steps: [
      {
        step: 1,
        title: "Nhặt rau",
        instruction:
          "Rau muống nhặt bỏ lá già, cắt khúc 5–6cm. Rửa qua 3 lần nước, để thật ráo.",
      },
      {
        step: 2,
        title: "Phi tỏi",
        instruction:
          "Bắc chảo lửa thật lớn, cho dầu vào đun nóng già. Phi tỏi đến vàng thơm — chú ý không để cháy đen.",
      },
      {
        step: 3,
        title: "Xào rau",
        instruction:
          "Cho rau muống vào xào nhanh tay liên tục ở lửa thật lớn. Nêm nước mắm và muối, đảo đều.",
      },
      {
        step: 4,
        title: "Dọn ra",
        instruction:
          "Xào đến khi rau chín tái (3–4 phút), giữ màu xanh đẹp. Rắc tiêu, dọn ngay ra đĩa — ăn nguội sẽ mất giòn.",
      },
    ],
    story:
      "Rau muống xào tỏi là món tôi học nấu đầu tiên. Hồi còn học lớp 8, mẹ ra ngoài chợ trễ, tôi tự xào một mình — tỏi cháy đen, rau mềm nhũn. Nhưng mẹ về vẫn khen ngon. Sau này tôi mới hiểu: người ăn không cần món ngon hoàn hảo, họ cần thấy mình được nghĩ đến.",
  },
  {
    id: 5,
    name: "Canh Khổ Qua Nhồi Thịt",
    slug: "canh-kho-qua-nhoi-thit",
    category: "canh",
    region: "Nam Bộ",
    description:
      "Canh khổ qua nhồi thịt — món ăn vừa đắng vừa ngọt, như chính cuộc đời. Vị đắng thanh của khổ qua hòa với nước dùng ngọt trong từ xương và thịt băm — người ăn quen rồi sẽ thấy thiếu nếu không có nó trong mâm cơm.",
    image: "https://images.unsplash.com/photo-1547592180-85f173990554?w=800&q=80",
    prepTime: "25 phút",
    cookTime: "35 phút",
    servings: 4,
    difficulty: "Trung bình",
    ingredients: [
      { amount: "4 quả", name: "khổ qua (mướp đắng) vừa" },
      { amount: "300g", name: "thịt heo băm nhuyễn" },
      { amount: "50g", name: "miến (bún tàu), ngâm mềm cắt ngắn" },
      { amount: "3 cái", name: "nấm mèo, ngâm mềm băm nhỏ" },
      { amount: "1 quả", name: "trứng gà" },
      { amount: "2 muỗng canh", name: "nước mắm" },
      { amount: "vừa đủ", name: "hành lá, tiêu, hạt nêm" },
    ],
    steps: [
      {
        step: 1,
        title: "Sơ chế khổ qua",
        instruction:
          "Khổ qua cắt đôi hoặc cắt khúc 5cm, dùng muỗng nạo sạch ruột và hột. Ngâm nước muối loãng 10 phút để bớt đắng.",
      },
      {
        step: 2,
        title: "Làm nhân",
        instruction:
          "Trộn thịt băm + miến + nấm mèo + trứng gà + nước mắm + tiêu. Đánh đều cho nhân kết dính.",
      },
      {
        step: 3,
        title: "Nhồi nhân",
        instruction:
          "Nhét nhân thịt vào lòng khổ qua, ém chặt hai đầu. Không nhồi quá căng để nhân không bị vỡ ra khi nấu.",
      },
      {
        step: 4,
        title: "Nấu canh",
        instruction:
          "Đun sôi 1.5 lít nước (có thể dùng nước xương hầm). Nhẹ nhàng cho khổ qua đã nhồi vào, nấu lửa vừa 25–30 phút.",
      },
      {
        step: 5,
        title: "Nêm gia vị",
        instruction:
          "Nêm nước mắm và hạt nêm cho vừa ăn. Rắc hành lá và tiêu lên trên. Múc ra tô, ăn nóng.",
      },
    ],
    story:
      "Ba tôi không thích khổ qua vì đắng. Nhưng mỗi khi mẹ nấu canh này, ông lại ăn hết phần mình. Ông nói: 'Cái đắng nó làm ngọt hơn.' Mãi sau tôi mới hiểu ông không chỉ nói về khổ qua.",
  },
  {
    id: 6,
    name: "Cơm Tấm Sườn Bì",
    slug: "com-tam-suon-bi",
    category: "com",
    region: "Sài Gòn",
    description:
      "Cơm tấm là linh hồn ẩm thực đường phố Sài Gòn — hạt cơm tấm trắng tinh với sườn nướng thơm lừng, bì trộn thính, chả trứng, mỡ hành xanh và nước mắm pha chua ngọt đặc trưng. Một đĩa cơm tấm là cả một buổi sáng Sài Gòn.",
    image: "https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=800&q=80",
    prepTime: "30 phút",
    cookTime: "30 phút",
    servings: 4,
    difficulty: "Khó",
    ingredients: [
      { amount: "400g", name: "gạo tấm" },
      { amount: "500g", name: "sườn non cắt miếng vừa" },
      { amount: "200g", name: "bì heo (da heo luộc thái sợi)" },
      { amount: "50g", name: "thính gạo rang" },
      { amount: "4 quả", name: "trứng gà (làm chả)" },
      { amount: "3 muỗng canh", name: "nước mắm (ướp sườn)" },
      { amount: "2 muỗng canh", name: "đường, sả băm, tỏi băm" },
      { amount: "1 mớ", name: "hành lá, mỡ hành" },
      { amount: "vừa đủ", name: "nước mắm pha, dưa chua, cà chua" },
    ],
    steps: [
      {
        step: 1,
        title: "Ướp sườn",
        instruction:
          "Ướp sườn với nước mắm, đường, sả băm, tỏi băm, tiêu tối thiểu 2 tiếng (qua đêm càng tốt).",
      },
      {
        step: 2,
        title: "Nấu cơm tấm",
        instruction:
          "Vo gạo tấm, đổ nước theo tỉ lệ 1:1.2. Nấu như cơm thường, sau khi sôi thì hạ lửa nhỏ riu riu.",
      },
      {
        step: 3,
        title: "Nướng sườn",
        instruction:
          "Nướng sườn trên vỉ than hoặc lò nướng 200°C khoảng 15–20 phút, trở đều. Phết thêm mỡ hành lên khi nướng.",
      },
      {
        step: 4,
        title: "Trộn bì",
        instruction:
          "Bì heo thái sợi trộn đều với thính gạo rang, thêm chút tỏi phi và tiêu. Bì phải trộn khi còn ấm để thính bám đều.",
      },
      {
        step: 5,
        title: "Dọn đĩa",
        instruction:
          "Xới cơm ra đĩa, xếp sườn nướng + bì + chả trứng chiên lên trên. Thêm mỡ hành, hành phi, dưa leo. Chan nước mắm pha.",
      },
    ],
    story:
      "Buổi sáng Sài Gòn không có gì bằng ngồi vỉa hè, đĩa cơm tấm bốc khói, ly cà phê sữa đá bên cạnh. Tiếng xe cộ ồn ào, mùi sườn nướng bay từ bếp than — đó là Sài Gòn của tôi, không bao giờ quên được dù đi xa bao lâu.",
  },
  {
    id: 7,
    name: "Bún Bò Huế",
    slug: "bun-bo-hue",
    category: "bun",
    region: "Huế",
    description:
      "Bún bò Huế — sắc đỏ cay nồng của nồi nước lèo xứ Huế, thơm mùi sả và mắm ruốc. Khoanh bún trắng mềm, miếng bò giòn dai, giò heo trong vắt — tô bún Huế là cả một cơn nắng miền Trung đổ vào lòng.",
    image: "https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=800&q=80",
    prepTime: "40 phút",
    cookTime: "120 phút",
    servings: 6,
    difficulty: "Khó",
    ingredients: [
      { amount: "500g", name: "bắp bò (thịt bò bắp)" },
      { amount: "2 cái", name: "giò heo (chân giò)" },
      { amount: "500g", name: "bún tươi (bún sợi to)" },
      { amount: "3 cây", name: "sả, đập dập" },
      { amount: "2 muỗng canh", name: "mắm ruốc Huế" },
      { amount: "2 muỗng canh", name: "sả băm, tỏi ớt băm" },
      { amount: "1 muỗng canh", name: "bột màu điều (ớt dầu)" },
      { amount: "vừa đủ", name: "nước mắm, đường, muối" },
      { amount: "1 mớ", name: "rau ăn kèm: bắp chuối, giá, rau thơm" },
    ],
    steps: [
      {
        step: 1,
        title: "Hầm xương",
        instruction:
          "Trần giò heo và bắp bò qua nước sôi. Hầm cùng 2 lít nước, sả và muối trong 60–90 phút cho nước trong và ngọt.",
      },
      {
        step: 2,
        title: "Phi gia vị",
        instruction:
          "Phi thơm sả băm + tỏi + ớt với dầu màu điều đến khi dậy mùi thơm đỏ đẹp. Cho vào nồi nước lèo.",
      },
      {
        step: 3,
        title: "Nêm mắm ruốc",
        instruction:
          "Pha mắm ruốc với chút nước ấm, cho vào nồi. Nêm nước mắm + đường cho vừa ăn — nước lèo phải đậm đà, hơi cay.",
      },
      {
        step: 4,
        title: "Chuẩn bị thịt",
        instruction:
          "Vớt bắp bò ra để nguội, thái lát mỏng. Giò heo thái miếng. Xếp riêng ra đĩa.",
      },
      {
        step: 5,
        title: "Dọn tô",
        instruction:
          "Chần bún qua nước sôi, xếp vào tô. Cho bắp bò + giò heo lên, chan nước lèo nóng. Ăn kèm bắp chuối thái mỏng, giá, rau thơm và ớt tươi.",
      },
    ],
    story:
      "Lần đầu tôi ăn bún bò ở Huế là một sáng mưa dầm, trong tiệm bà cụ ở đường Nguyễn Bỉnh Khiêm. Tô bún đặt xuống, hơi nóng bốc lên — mùi sả và mắm ruốc quyện vào nhau, lạ mà quen. Kể từ đó, mỗi lần nhớ Huế là nhớ cái mùi đó.",
  },
  {
    id: 8,
    name: "Bánh Canh Cua",
    slug: "banh-canh-cua",
    category: "bun",
    region: "Nam Bộ",
    description:
      "Bánh canh cua — sợi bánh canh bột lọc dày mịn, sánh đặc trong nước dùng cua đồng béo ngậy màu cam san hô. Thêm tôm, chả cua, hành phi vàng — một tô bánh canh cua là bữa sáng no nê, ấm lòng nhất của người miền Nam.",
    image: "https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=800&q=80",
    prepTime: "30 phút",
    cookTime: "45 phút",
    servings: 4,
    difficulty: "Trung bình",
    ingredients: [
      { amount: "400g", name: "bánh canh bột lọc (sợi to)" },
      { amount: "500g", name: "cua đồng (3–4 con)" },
      { amount: "200g", name: "tôm sú, bóc vỏ" },
      { amount: "200g", name: "chả cua (tùy chọn)" },
      { amount: "2 muỗng canh", name: "dầu hành phi" },
      { amount: "3 muỗng canh", name: "nước mắm" },
      { amount: "vừa đủ", name: "hành lá, ngò rí, tiêu, ớt" },
    ],
    steps: [
      {
        step: 1,
        title: "Nấu nước cua",
        instruction:
          "Giã cua với chút muối, lọc qua rây lấy nước. Đun nước cua trên lửa vừa, khuấy theo một chiều đến khi gạch cua nổi lên — vớt gạch để riêng.",
      },
      {
        step: 2,
        title: "Nấu nước dùng",
        instruction:
          "Cho nước cua đã lọc vào nồi lớn cùng 1.5 lít nước. Đun sôi, nêm nước mắm và muối. Cho tôm vào nấu chín.",
      },
      {
        step: 3,
        title: "Chần bánh canh",
        instruction:
          "Chần bánh canh qua nước sôi 1–2 phút cho mềm và sạch bột dư. Vớt ra, xả nước lạnh.",
      },
      {
        step: 4,
        title: "Làm sệt nước dùng",
        instruction:
          "Hòa bột năng với nước lạnh, từ từ đổ vào nồi nước dùng đang sôi, khuấy đều cho đến khi nước sánh lại.",
      },
      {
        step: 5,
        title: "Dọn tô",
        instruction:
          "Xếp bánh canh vào tô, chan nước dùng nóng lên. Xếp gạch cua, tôm, chả cua. Rắc hành lá, ngò, tiêu, thêm chút dầu hành vàng ươm.",
      },
    ],
    story:
      "Xe bánh canh bà Tư ở đầu hẻm mở từ năm giờ sáng, khói bốc nghi ngút. Hồi học tiểu học, tôi hay theo ba ghé mua trước khi đến trường. Tô bánh canh cua nóng hổi, thêm miếng chả cua vàng ruộm — đó là ký ức sáng sớm trong lành nhất của tôi.",
  },
];

export const categories = [
  { value: "all", label: "Tất Cả" },
  { value: "canh", label: "Canh & Súp" },
  { value: "kho", label: "Kho & Rim" },
  { value: "xao", label: "Xào & Chiên" },
  { value: "luoc", label: "Luộc & Hấp" },
  { value: "com", label: "Cơm" },
  { value: "bun", label: "Bún & Phở" },
];
