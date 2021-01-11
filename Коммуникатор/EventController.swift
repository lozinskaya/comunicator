//
//  EventController.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 11.01.2021.
//

import Foundation
import UIKit

class EventController: UIViewController {
   
    //массив данных
    var data = [["День кофе: пьем и не спим", "Собираемся, наливаем, выпиваем и уходим. Перед уходом платим, наличными конечно.", "7 января в 19:00", "До 60 человек"], ["День еды: собираемся, объедаемся и уходим", "Все как обычно, вы можете наесться за 10 минут – тогда ваш ужин обойдется вам в 10₽. Вам выгодно, а нам нет.", "7 января в 19:00", "До 60 человек"]]
    let idCell = "MailCell"
    @IBOutlet weak var tableEvents: UITableView!
    override func viewDidLoad() {
        super.viewDidLoad()
        tableEvents.dataSource = self
        tableEvents.delegate = self
        
        tableEvents.register(UINib(nibName: "MainTableViewCell", bundle: nil ), forCellReuseIdentifier: idCell)
    }
}

extension EventController: UITableViewDataSource, UITableViewDelegate{
    
    //количество создаваемых клеток
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return self.data.count
    }
    //создание клетки
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let cell = tableEvents.dequeueReusableCell(withIdentifier: idCell) as! MainTableViewCell
        cell.titleEvent.text = self.data[indexPath.row][0]
        cell.descriptionEvent.text = self.data[indexPath.row][1]
        cell.imgEvent.image = UIImage(named: "eventImg")
        cell.timeEvent.text = self.data[indexPath.row][2]
        cell.countPersonsEvent.text = self.data[indexPath.row][3]
        
        return cell
    }
    //высота клетки
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return 313.0
    }
    //вывод индекса выбранной строки
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        print("\(indexPath.row)")
        performSegue(withIdentifier: "showdetail", sender: self)
    }
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        if let destination = segue.destination as? DetailVC {
            destination.chooseCell = data[(tableEvents.indexPathForSelectedRow?.row)!]
        }
    }
}
